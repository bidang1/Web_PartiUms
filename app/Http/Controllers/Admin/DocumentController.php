<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubEvent;
use App\Models\SubEventDocument;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(SubEvent $subEvent)
    {
        $documents = $subEvent->documents()->orderBy('order')->get();
        return view('admin.sub-events.documents', compact('subEvent', 'documents'));
    }

    public function store(Request $request, SubEvent $subEvent)
    {
        $maxSizeKb = config('parti.max_upload_size_mb', 10) * 1024;
        $allowedTypes = implode(',', config('parti.allowed_file_types', ['pdf', 'docx']));

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:' . $allowedTypes, 'max:' . $maxSizeKb],
            'order' => ['required', 'integer', 'min:0'],
        ]);

        $file = $request->file('file');
        $extension = in_array(strtolower($file->extension()), config('parti.allowed_file_types', ['pdf', 'docx'])) 
            ? strtolower($file->extension()) 
            : (in_array(strtolower($file->getClientOriginalExtension()), config('parti.allowed_file_types', ['pdf', 'docx'])) ? strtolower($file->getClientOriginalExtension()) : 'pdf');

        $path = $file->store('documents', 'public');

        $document = $subEvent->documents()->create([
            'label' => $validated['label'],
            'file_path' => $path,
            'file_type' => $extension,
            'file_size_bytes' => $file->getSize(),
            'order' => $validated['order'],
            'uploaded_by' => \Illuminate\Support\Facades\Auth::id(),
            'uploaded_at' => now(),
        ]);

        // Audit Log
        AuditLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'Mengunggah dokumen template "' . $document->label . '" untuk sub acara "' . $subEvent->name . '"',
            'entity_type' => 'SubEventDocument',
            'entity_id' => $document->id,
        ]);

        return back()->with('success', 'Dokumen template berhasil diunggah.');
    }

    public function update(Request $request, SubEventDocument $document)
    {
        $maxSizeKb = config('parti.max_upload_size_mb', 10) * 1024;
        $allowedTypes = implode(',', config('parti.allowed_file_types', ['pdf', 'docx']));

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'file' => ['nullable', 'file', 'mimes:' . $allowedTypes, 'max:' . $maxSizeKb],
            'order' => ['required', 'integer', 'min:0'],
        ]);

        $data = [
            'label' => $validated['label'],
            'order' => $validated['order'],
        ];

        if ($request->hasFile('file')) {
            // Delete old file
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            // Save new file
            $file = $request->file('file');
            $extension = in_array(strtolower($file->extension()), config('parti.allowed_file_types', ['pdf', 'docx'])) 
                ? strtolower($file->extension()) 
                : (in_array(strtolower($file->getClientOriginalExtension()), config('parti.allowed_file_types', ['pdf', 'docx'])) ? strtolower($file->getClientOriginalExtension()) : 'pdf');

            $data['file_path'] = $file->store('documents', 'public');
            $data['file_type'] = $extension;
            $data['file_size_bytes'] = $file->getSize();
            $data['uploaded_by'] = \Illuminate\Support\Facades\Auth::id();
            $data['uploaded_at'] = now();
        }

        $document->update($data);

        $subEventName = $document->subEvent?->name ?? 'Sub Acara';

        AuditLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'Memperbarui dokumen template "' . $document->label . '" pada sub acara "' . $subEventName . '"',
            'entity_type' => 'SubEventDocument',
            'entity_id' => $document->id,
        ]);

        return back()->with('success', 'Dokumen template berhasil diperbarui.');
    }

    public function destroy(SubEventDocument $document)
    {
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $subEventName = $document->subEvent?->name ?? 'Sub Acara';

        AuditLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'Menghapus dokumen template "' . $document->label . '" dari sub acara "' . $subEventName . '"',
            'entity_type' => 'SubEventDocument',
            'entity_id' => $document->id,
        ]);

        $document->delete();

        return back()->with('success', 'Dokumen template berhasil dihapus.');
    }


}

