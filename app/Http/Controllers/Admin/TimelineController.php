<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimelineItem;
use App\Models\SubEvent;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function index()
    {
        $year = session('active_year', config('parti.active_year', 2026));
        $timeline = TimelineItem::forYear($year)
            ->with('subEvent')
            ->orderBy('date')
            ->orderBy('order')
            ->get();

        return view('admin.timeline.index', compact('timeline', 'year'));
    }

    public function create()
    {
        $year = session('active_year', config('parti.active_year', 2026));
        $subEvents = SubEvent::forYear($year)->notDeleted()->orderBy('name')->get();

        return view('admin.timeline.create', compact('subEvents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sub_event_id' => ['nullable', 'exists:sub_events,id'],
            'date' => ['required', 'date'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'order' => ['required', 'integer', 'min:0'],
        ]);

        $year = session('active_year', config('parti.active_year', 2026));

        $item = TimelineItem::create([
            'year' => $year,
            'sub_event_id' => $validated['sub_event_id'],
            'date' => $validated['date'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'order' => $validated['order'],
        ]);

        // Audit Log
        AuditLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'Menambahkan timeline baru: ' . $item->title,
            'entity_type' => 'TimelineItem',
            'entity_id' => $item->id,
        ]);

        return redirect()->route('admin.timeline.index')->with('success', 'Agenda timeline berhasil ditambahkan.');
    }

    public function edit(TimelineItem $timeline)
    {
        $year = session('active_year', config('parti.active_year', 2026));
        $subEvents = SubEvent::forYear($year)->notDeleted()->orderBy('name')->get();

        return view('admin.timeline.edit', compact('timeline', 'subEvents'));
    }

    public function update(Request $request, TimelineItem $timeline)
    {
        $validated = $request->validate([
            'sub_event_id' => ['nullable', 'exists:sub_events,id'],
            'date' => ['required', 'date'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'order' => ['required', 'integer', 'min:0'],
        ]);

        $timeline->update([
            'sub_event_id' => $validated['sub_event_id'],
            'date' => $validated['date'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'order' => $validated['order'],
        ]);

        // Audit Log
        AuditLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'Mengubah agenda timeline: ' . $timeline->title,
            'entity_type' => 'TimelineItem',
            'entity_id' => $timeline->id,
        ]);

        return redirect()->route('admin.timeline.index')->with('success', 'Agenda timeline berhasil diperbarui.');
    }

    public function destroy(TimelineItem $timeline)
    {
        // Audit Log before deletion
        AuditLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'Menghapus agenda timeline: ' . $timeline->title,
            'entity_type' => 'TimelineItem',
            'entity_id' => $timeline->id,
        ]);

        $timeline->delete();

        return redirect()->route('admin.timeline.index')->with('success', 'Agenda timeline berhasil dihapus.');
    }


}

