<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubEvent;
use App\Models\AuditLog;
use App\Http\Requests\GformLinkRequest;

class RegistrationLinkController extends Controller
{
    public function index()
    {
        $year = session('active_year', config('parti.active_year', 2026));
        $subEvents = SubEvent::forYear($year)->notDeleted()->orderBy('order')->get();

        return view('admin.registration-links.index', compact('subEvents', 'year'));
    }

    public function update(GformLinkRequest $request, SubEvent $subEvent)
    {
        $links = $request->gform_links ?? [];
        
        $subEvent->update([
            'gform_link' => empty($links) ? null : $links,
            'gform_updated_by' => \Illuminate\Support\Facades\Auth::id(),
            'gform_updated_at' => now(),
        ]);

        // Audit Log
        AuditLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'Memperbarui tautan Google Form sub acara "' . $subEvent->name . '" menjadi ' . count($links) . ' tautan',
            'entity_type' => 'SubEvent',
            'entity_id' => $subEvent->id,
        ]);

        return redirect()->back()->with('success', 'Tautan pendaftaran untuk ' . $subEvent->name . ' berhasil diperbarui.');
    }

    public function toggleRegistration(SubEvent $subEvent)
    {
        $newStatus = !($subEvent->is_registration_open ?? true);
        $subEvent->update([
            'is_registration_open' => $newStatus,
        ]);

        $stateText = $newStatus ? 'dibuka' : 'ditutup';

        AuditLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => "Pendaftaran sub acara \"{$subEvent->name}\" {$stateText}",
            'entity_type' => 'SubEvent',
            'entity_id' => $subEvent->id,
        ]);

        return redirect()->back()->with('success', "Pendaftaran untuk {$subEvent->name} berhasil {$stateText}.");
    }
}

