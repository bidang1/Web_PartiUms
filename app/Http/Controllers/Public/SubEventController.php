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
            ->with([
                'documents' => function ($query) {
                    $query->orderBy('order');
                },
                'timelineItems' => function ($query) {
                    $query->orderBy('date')->orderBy('order');
                }
            ])
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

    /**
     * Menyajikan thumbnail gambar Open Graph berukuran ringan (< 200KB) untuk preview WhatsApp & media sosial.
     */
    public function ogImage(string $slug)
    {
        // ponytail: Serve lightweight JPEG (< 200KB) so WhatsApp status, IG & crawlers unfurl properly without 300KB cutoff
        $year = config('parti.active_year', 2026);
        $subEvent = SubEvent::where('slug', $slug)
            ->published()
            ->notDeleted()
            ->orderByRaw('CASE WHEN year = ? THEN 0 ELSE 1 END', [$year])
            ->first();

        $defaultLogo = public_path('logo.png');
        if (!$subEvent || !$subEvent->poster_path) {
            return file_exists($defaultLogo)
                ? response()->file($defaultLogo, ['Content-Type' => 'image/png', 'Cache-Control' => 'public, max-age=86400'])
                : abort(404);
        }

        $filePath = storage_path('app/public/' . $subEvent->poster_path);
        if (!file_exists($filePath)) {
            $filePath = public_path('storage/' . $subEvent->poster_path);
        }

        if (!file_exists($filePath)) {
            return file_exists($defaultLogo)
                ? response()->file($defaultLogo, ['Content-Type' => 'image/png', 'Cache-Control' => 'public, max-age=86400'])
                : abort(404);
        }

        $cacheDir = storage_path('app/public/posters/og');
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }

        $thumbName = 'og_' . md5($subEvent->poster_path . '_' . filemtime($filePath)) . '.jpg';
        $thumbPath = $cacheDir . '/' . $thumbName;

        if (file_exists($thumbPath)) {
            return response()->file($thumbPath, [
                'Content-Type' => 'image/jpeg',
                'Cache-Control' => 'public, max-age=604800, immutable',
            ]);
        }

        // Generate optimized OG image using native GD
        if (extension_loaded('gd')) {
            $info = @getimagesize($filePath);
            if ($info) {
                $src = match ($info['mime'] ?? '') {
                    'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($filePath),
                    'image/png' => @imagecreatefrompng($filePath),
                    'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($filePath) : null,
                    default => null,
                };

                if ($src) {
                    $origW = imagesx($src);
                    $origH = imagesy($src);

                    // Resize to max 800px width (optimal for WhatsApp mobile thumbnails & fast fetch)
                    $targetW = min($origW, 800);
                    $targetH = (int) round($origH * ($targetW / $origW));

                    $thumb = imagecreatetruecolor($targetW, $targetH);
                    $white = imagecolorallocate($thumb, 255, 255, 255);
                    imagefilledrectangle($thumb, 0, 0, $targetW, $targetH, $white);
                    imagecopyresampled($thumb, $src, 0, 0, 0, 0, $targetW, $targetH, $origW, $origH);

                    if (@imagejpeg($thumb, $thumbPath, 80)) {
                        imagedestroy($src);
                        imagedestroy($thumb);
                        return response()->file($thumbPath, [
                            'Content-Type' => 'image/jpeg',
                            'Cache-Control' => 'public, max-age=604800, immutable',
                        ]);
                    }

                    imagedestroy($src);
                    imagedestroy($thumb);
                }
            }
        }

        return response()->file($filePath);
    }
}
