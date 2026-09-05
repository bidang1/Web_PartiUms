<?php

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\SubEventController as PublicSubEventController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ChangePasswordController;
use App\Http\Controllers\Admin\RegistrationLinkController;
use App\Http\Controllers\Admin\SubEventController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\TimelineController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SponsorController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\AuditLogController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Public\FaqController as PublicFaqController;

// Halaman publik
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', [HomeController::class, 'about'])->name('about');
Route::get('/faq', [PublicFaqController::class, 'index'])->name('faq');
Route::get('/acara/{slug}', [PublicSubEventController::class, 'show'])->name('sub-event.show');
Route::get('/dokumen/{document}/download', [PublicSubEventController::class, 'download'])->name('document.download');

// Rute otentikasi
require __DIR__.'/auth.php';

// Pengalihan route /dashboard bawaan Breeze langsung ke dashboard admin
Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'))->name('dashboard');

// Panel admin (memerlukan login dan verifikasi ganti password)
Route::middleware(['auth', 'force.password.change'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard Admin (Superadmin & Kesekretariatan)
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Ganti Password Admin (Throttled: Maksimal 6 percobaan per menit)
        Route::get('/change-password', [ChangePasswordController::class, 'edit'])->name('change-password');
        Route::put('/change-password', [ChangePasswordController::class, 'update'])->middleware('throttle:6,1')->name('change-password.update');

        // Manajemen Link Pendaftaran Event
        Route::get('/registration-links', [RegistrationLinkController::class, 'index'])->name('registration-links.index');
        Route::put('/registration-links/{subEvent}', [RegistrationLinkController::class, 'update'])->name('registration-links.update');

        // Rute khusus Superadmin
        Route::middleware('role:SUPERADMIN')->group(function () {
            // Ubah Tahun Aktif Global (Hanya Superadmin)
            Route::post('/change-year', [DashboardController::class, 'changeYear'])->name('change-year');

            // Kelola Sub-Acara / Lomba
            Route::resource('sub-events', SubEventController::class)->except(['show']);
            Route::put('sub-events/{subEvent}/status', [SubEventController::class, 'updateStatus'])->name('sub-events.status');

            // Kelola Dokumen Sub-Acara
            Route::get('sub-events/{subEvent}/documents', [DocumentController::class, 'index'])->name('documents.index');
            Route::post('sub-events/{subEvent}/documents', [DocumentController::class, 'store'])->name('documents.store');
            Route::put('documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
            Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

            // Kelola Timeline / Alur Waktu
            Route::resource('timeline', TimelineController::class)->except(['show']);

            // Kelola Pengguna / Panitia
            Route::resource('users', UserController::class)->only(['index', 'create', 'store']);
            Route::put('users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
            Route::put('users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
            Route::put('users/{user}/reset-password', [UserController::class, 'resetPassword'])->middleware('throttle:6,1')->name('users.reset-password');


            // Kelola Data Sponsor
            Route::resource('sponsors', SponsorController::class)->except(['show']);

            // Kelola FAQ
            Route::resource('faqs', FaqController::class)->except(['show']);

            // Log Aktivitas Sistem
            Route::get('audit-log', [AuditLogController::class, 'index'])->name('audit-log.index');

            // Endpoint pemeliharaan server (dibatasi throttle 10 per menit)
            Route::middleware('throttle:10,1')->group(function () {
                Route::post('create-symlink', function () {
                    try {
                        \Illuminate\Support\Facades\Artisan::call('storage:link');
                        return response()->json(['status' => 'success', 'message' => 'Storage symlink created successfully!']);
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('Failed to create storage symlink: ' . $e->getMessage());
                        return response()->json(['status' => 'error', 'message' => 'Gagal membuat tautan storage.'], 500);
                    }
                })->name('maintenance.symlink');

                Route::post('clear-cache', function () {
                    try {
                        \Illuminate\Support\Facades\Artisan::call('config:clear');
                        \Illuminate\Support\Facades\Artisan::call('cache:clear');
                        \Illuminate\Support\Facades\Artisan::call('view:clear');
                        \Illuminate\Support\Facades\Artisan::call('route:clear');
                        return response()->json(['status' => 'success', 'message' => 'Semua cache berhasil dibersihkan!']);
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('Failed to clear caches: ' . $e->getMessage());
                        return response()->json(['status' => 'error', 'message' => 'Gagal membersihkan cache aplikasi.'], 500);
                    }
                })->name('maintenance.clear-cache');
            });
        });
    });

// Handler penyaji berkas media & gambar dengan proteksi Path Traversal
$mediaHandler = function ($path) {
    if (str_contains($path, '..') || str_contains($path, '\\')) {
        abort(404);
    }
    $cleanPath = ltrim($path, '/');

    // Protect draft or deleted sub-event documents from unauthenticated direct exposure
    if (str_starts_with($cleanPath, 'sub-events/documents/')) {
        $doc = \App\Models\SubEventDocument::where('file_path', $cleanPath)->with('subEvent')->first();
        if (!$doc || !$doc->subEvent || $doc->subEvent->status !== 'PUBLISHED' || $doc->subEvent->is_deleted) {
            abort(404);
        }
    }

    // 1. Cek berkas di folder storage/app/public/
    $baseStorage = realpath(storage_path('app/public'));
    $fullPath = storage_path('app/public/' . $cleanPath);
    $realFullPath = realpath($fullPath);
    if ($baseStorage && $realFullPath && str_starts_with($realFullPath, $baseStorage) && is_file($realFullPath)) {
        return response()->file($realFullPath, [
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    // 2. Cek berkas di folder public/storage/
    $basePublic = realpath(public_path('storage'));
    $publicPath = public_path('storage/' . $cleanPath);
    $realPublicPath = realpath($publicPath);
    if ($basePublic && $realPublicPath && str_starts_with($realPublicPath, $basePublic) && is_file($realPublicPath)) {
        return response()->file($realPublicPath, [
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    abort(404);
};

Route::get('/media/{path}', $mediaHandler)->where('path', '.*')->name('media.show');
Route::get('/storage/{path}', $mediaHandler)->where('path', '.*');

// Peta situs dinamis untuk SEO
Route::get('/sitemap.xml', function () {
    $activeYear = \App\Models\Setting::get('active_year', config('parti.active_year', 2026));
    $subEvents = \App\Models\SubEvent::where('year', $activeYear)
        ->published()
        ->notDeleted()
        ->get();

    $urls = [
        ['loc' => route('home'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'daily', 'priority' => '1.0'],
        ['loc' => route('about'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'weekly', 'priority' => '0.8'],
        ['loc' => route('faq'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'daily', 'priority' => '0.9'],
    ];

    foreach ($subEvents as $sub) {
        $urls[] = [
            'loc' => route('sub-event.show', $sub->slug),
            'lastmod' => $sub->updated_at ? $sub->updated_at->toAtomString() : now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.9',
        ];
    }

    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ($urls as $url) {
        $xml .= '<url>';
        $xml .= '<loc>' . htmlspecialchars($url['loc']) . '</loc>';
        $xml .= '<lastmod>' . $url['lastmod'] . '</lastmod>';
        $xml .= '<changefreq>' . $url['changefreq'] . '</changefreq>';
        $xml .= '<priority>' . $url['priority'] . '</priority>';
        $xml .= '</url>';
    }
    $xml .= '</urlset>';

    return response($xml, 200, [
        'Content-Type' => 'application/xml',
        'Cache-Control' => 'public, max-age=86400',
    ]);
});

Route::get('/robots.txt', function () {
    $robots = "# --------------------------------------------------\n";
    $robots .= "# PARTI 2026 Official Platform - v1.0.0 (Codename: Vanguard)\n";
    $robots .= "# Engineered by AtnanLabs (https://www.atnan.my.id/)\n";
    $robots .= "# --------------------------------------------------\n\n";
    $robots .= "User-agent: *\n";
    $robots .= "Allow: /\n";
    $robots .= "Disallow: /admin/\n";
    $robots .= "Disallow: /dashboard\n";
    $robots .= "Disallow: /auth\n";
    $robots .= "Disallow: /login\n\n";
    $robots .= "# AI Crawlers allowed for GEO (Generative Engine Optimization)\n";
    $robots .= "User-agent: GPTBot\nAllow: /\n\n";
    $robots .= "User-agent: PerplexityBot\nAllow: /\n\n";
    $robots .= "User-agent: ClaudeBot\nAllow: /\n\n";
    $robots .= "User-agent: Google-Extended\nAllow: /\n\n";
    $robots .= "User-agent: GoogleOther\nAllow: /\n\n";
    $robots .= "User-agent: Googlebot\nAllow: /\n\n";
    $robots .= "Sitemap: " . url('/sitemap.xml') . "\n";

    return response($robots, 200, [
        'Content-Type' => 'text/plain',
        'Cache-Control' => 'public, max-age=86400',
    ]);
});