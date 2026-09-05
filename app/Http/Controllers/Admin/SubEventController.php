<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubEvent;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubEventController extends Controller
{
    public function index()
    {
        $year = session('active_year', config('parti.active_year', 2026));
        $subEvents = SubEvent::forYear($year)->notDeleted()->orderBy('order')->get();

        return view('admin.sub-events.index', compact('subEvents', 'year'));
    }

    public function create()
    {
        return view('admin.sub-events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date_start' => ['nullable', 'date'],
            'date_end' => ['nullable', 'date', 'after_or_equal:date_start'],
            'pj_names' => ['nullable', 'string'],
            'htm_tiers' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    $lines = array_filter(array_map('trim', explode("\n", str_replace("\r", "", $value))));
                    foreach ($lines as $line) {
                        $parts = explode(':', $line, 2);
                        if (count($parts) < 2 || trim($parts[0]) === '' || !is_numeric(trim($parts[1]))) {
                            $fail('Format HTM Tiket harus berupa "NamaKategori:Harga" per baris (contoh: Presale:20000).');
                            return;
                        }
                    }
                }
            ],
            'order' => ['required', 'integer', 'min:0'],
            'type' => ['required', 'in:ONLINE,OFFLINE,HYBRID'],
            'location' => ['nullable', 'string', 'max:255'],
            'poster' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        $year = session('active_year', config('parti.active_year', 2026));

        // Parse PJ Names (comma separated)
        $pjNames = [];
        if (!empty($validated['pj_names'])) {
            $pjNames = array_filter(array_map('trim', explode(',', $validated['pj_names'])));
        }

        // Parse HTM Tiers (label:price per line)
        $htmTiers = [];
        if (!empty($validated['htm_tiers'])) {
            $lines = explode("\n", str_replace("\r", "", $validated['htm_tiers']));
            foreach ($lines as $line) {
                $parts = explode(':', $line, 2);
                if (count($parts) === 2) {
                    $htmTiers[] = [
                        'label' => trim($parts[0]),
                        'price' => (int) trim($parts[1]),
                    ];
                }
            }
        }

        $posterPath = null;
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
        }

        $subEvent = SubEvent::create([
            'year' => $year,
            'name' => $validated['name'],
            'tagline' => $validated['tagline'],
            'description' => $validated['description'],
            'date_start' => $validated['date_start'],
            'date_end' => $validated['date_end'],
            'pj_names' => $pjNames,
            'htm_tiers' => $htmTiers,
            'status' => 'DRAFT',
            'order' => $validated['order'],
            'is_deleted' => false,
            'type' => $validated['type'],
            'location' => $validated['location'],
            'poster_path' => $posterPath,
        ]);

        // Audit Log
        AuditLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'Membuat sub acara baru: ' . $subEvent->name,
            'entity_type' => 'SubEvent',
            'entity_id' => $subEvent->id,
        ]);

        return redirect()->route('admin.sub-events.index')->with('success', 'Sub acara berhasil ditambahkan.');
    }

    public function edit(SubEvent $subEvent)
    {
        // Format arrays back to strings for form editing
        $pjNamesString = $subEvent->pj_names ? implode(', ', $subEvent->pj_names) : '';
        
        $htmTiersString = '';
        if ($subEvent->htm_tiers) {
            $tierLines = [];
            foreach ($subEvent->htm_tiers as $tier) {
                $tierLines[] = $tier['label'] . ':' . $tier['price'];
            }
            $htmTiersString = implode("\n", $tierLines);
        }

        return view('admin.sub-events.edit', compact('subEvent', 'pjNamesString', 'htmTiersString'));
    }

    public function update(Request $request, SubEvent $subEvent)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date_start' => ['nullable', 'date'],
            'date_end' => ['nullable', 'date', 'after_or_equal:date_start'],
            'pj_names' => ['nullable', 'string'],
            'htm_tiers' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    $lines = array_filter(array_map('trim', explode("\n", str_replace("\r", "", $value))));
                    foreach ($lines as $line) {
                        $parts = explode(':', $line, 2);
                        if (count($parts) < 2 || trim($parts[0]) === '' || !is_numeric(trim($parts[1]))) {
                            $fail('Format HTM Tiket harus berupa "NamaKategori:Harga" per baris (contoh: Presale:20000).');
                            return;
                        }
                    }
                }
            ],
            'order' => ['required', 'integer', 'min:0'],
            'type' => ['required', 'in:ONLINE,OFFLINE,HYBRID'],
            'location' => ['nullable', 'string', 'max:255'],
            'poster' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        // Parse PJ Names (comma separated)
        $pjNames = [];
        if (!empty($validated['pj_names'])) {
            $pjNames = array_filter(array_map('trim', explode(',', $validated['pj_names'])));
        }

        // Parse HTM Tiers (label:price per line)
        $htmTiers = [];
        if (!empty($validated['htm_tiers'])) {
            $lines = explode("\n", str_replace("\r", "", $validated['htm_tiers']));
            foreach ($lines as $line) {
                $parts = explode(':', $line, 2);
                if (count($parts) === 2) {
                    $htmTiers[] = [
                        'label' => trim($parts[0]),
                        'price' => (int) trim($parts[1]),
                    ];
                }
            }
        }

        $data = [
            'name' => $validated['name'],
            'tagline' => $validated['tagline'],
            'description' => $validated['description'],
            'date_start' => $validated['date_start'],
            'date_end' => $validated['date_end'],
            'pj_names' => $pjNames,
            'htm_tiers' => $htmTiers,
            'order' => $validated['order'],
            'type' => $validated['type'],
            'location' => $validated['location'],
        ];

        if ($request->hasFile('poster')) {
            // Delete old file
            if ($subEvent->poster_path && Storage::disk('public')->exists($subEvent->poster_path)) {
                Storage::disk('public')->delete($subEvent->poster_path);
            }

            // Save new file
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $subEvent->update($data);

        // Audit Log
        AuditLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'Mengubah detail sub acara: ' . $subEvent->name,
            'entity_type' => 'SubEvent',
            'entity_id' => $subEvent->id,
        ]);

        return redirect()->route('admin.sub-events.index')->with('success', 'Sub acara berhasil diperbarui.');
    }

    public function destroy(SubEvent $subEvent)
    {
        // Delete poster file if exists
        if ($subEvent->poster_path && Storage::disk('public')->exists($subEvent->poster_path)) {
            Storage::disk('public')->delete($subEvent->poster_path);
            $subEvent->poster_path = null;
        }

        // Also delete all associated document files and delete the document records
        foreach ($subEvent->documents as $document) {
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
            $document->delete();
        }

        $subEvent->is_deleted = true;
        $subEvent->save();

        // Audit Log
        AuditLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'Menghapus sub acara (soft-delete) beserta poster & berkasnya: ' . $subEvent->name,
            'entity_type' => 'SubEvent',
            'entity_id' => $subEvent->id,
        ]);

        return redirect()->route('admin.sub-events.index')->with('success', 'Sub acara berhasil dihapus.');
    }

    public function updateStatus(Request $request, SubEvent $subEvent)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:DRAFT,PUBLISHED,CLOSED'],
        ]);

        $oldStatus = $subEvent->status;
        $subEvent->update(['status' => $validated['status']]);

        // Audit Log
        AuditLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'Mengubah status sub acara "' . $subEvent->name . '" dari "' . $oldStatus . '" menjadi "' . $validated['status'] . '"',
            'entity_type' => 'SubEvent',
            'entity_id' => $subEvent->id,
        ]);

        return redirect()->route('admin.sub-events.index')->with('success', 'Status sub acara ' . $subEvent->name . ' berhasil diperbarui.');
    }


}

