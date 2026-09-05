<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\SubEvent;
use App\Models\SubEventDocument;

class SubEventController extends Controller
{
    public function show(string $slug)
    {
        $year = config('parti.active_year', 2026);

        $subEvent = SubEvent::where('slug', $slug)
            ->published()
            ->notDeleted()
            ->with(['documents' => function ($query) {
                $query->orderBy('order');
            }])
            ->orderByRaw('CASE WHEN year = ? THEN 0 ELSE 1 END', [$year])
            ->firstOrFail();

        return view('public.sub-event-detail', compact('subEvent'));
    }

    public function download(SubEventDocument $document)
    {
        // Validasi keamanan: Pastikan dokumen terhubung ke sub-acara publik yang aktif
        $document->loadMissing('subEvent');
        if (!$document->subEvent || $document->subEvent->status !== 'PUBLISHED' || $document->subEvent->is_deleted) {
            abort(404, 'File tidak ditemukan.');
        }

        $path = storage_path('app/public/' . $document->file_path);
        
        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $safeLabel = \Illuminate\Support\Str::slug($document->label) ?: 'dokumen';
        $extension = in_array(strtolower($document->file_type), ['pdf', 'docx']) ? strtolower($document->file_type) : 'pdf';
        $safeFilename = $safeLabel . '.' . $extension;

        return response()->download($path, $safeFilename);
    }
}
