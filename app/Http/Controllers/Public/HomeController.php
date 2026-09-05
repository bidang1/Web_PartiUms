<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\SubEvent;
use App\Models\TimelineItem;
use App\Models\Sponsor;
use Illuminate\View\View;

/**
 * Controller Halaman Utama Publik
 *
 * Menangani penyajian data landing page utama event PARTI, meliputi daftar cabang perlombaan
 * (sub-events), tahapan timeline pelaksanaan, serta daftar mitra/sponsor aktif.
 */
class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (landing page) publik PARTI.
     *
     * Tahun aktif pelaksanaan ditentukan melalui session atau fallback konfigurasi `parti.active_year`.
     * Keputusan ini dibuat agar platform dapat menampilkan arsip data event tahun lalu tanpa mengubah struktur database.
     */
    public function index(): View
    {
        $year = config('parti.active_year', 2026);

        $subEvents = SubEvent::forYear($year)->published()->notDeleted()->orderBy('order')->get();
        $timeline = TimelineItem::forYear($year)->orderBy('date')->orderBy('order')->get();
        $sponsors = Sponsor::forYear($year)->active()->orderBy('tier')->orderBy('order')->get();

        return view('public.home', compact('subEvents', 'timeline', 'sponsors'));
    }

    /**
     * Menampilkan halaman khusus "Tentang PARTI".
     */
    public function about(): View
    {
        $year = config('parti.active_year', 2026);
        $subEvents = SubEvent::forYear($year)->published()->notDeleted()->orderBy('order')->get();
        $sponsors = Sponsor::forYear($year)->active()->orderBy('tier')->orderBy('order')->get();

        return view('public.about', compact('subEvents', 'sponsors'));
    }
}

