<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Sponsor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Controller Manajemen Sponsor (Panel Admin)
 *
 * Menangani operasi CRUD (Create, Read, Update, Delete) untuk pengelolaan data sponsor event.
 * Setiap tindakan modifikasi dicatat secara otomatis ke AuditLog sebagai jejak rekam aktivitas admin.
 */
class SponsorController extends Controller
{
    /**
     * Menampilkan daftar sponsor berdasarkan tahun aktif pelaksanaan event.
     */
    public function index(): View
    {
        $year = session('active_year', config('parti.active_year', 2026));
        $sponsors = Sponsor::forYear($year)->orderBy('tier')->orderBy('order')->get();

        return view('admin.sponsors.index', compact('sponsors', 'year'));
    }

    /**
     * Menampilkan formulir untuk menambahkan data sponsor baru.
     */
    public function create(): View
    {
        return view('admin.sponsors.create');
    }

    /**
     * Menyimpan data sponsor baru ke database dan mengunggah berkas logo.
     *
     * Ukuran logo dibatasi maksimum 1MB untuk memastikan kinerja pemuatan halaman depan tetap responsif.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'tier' => ['required', 'in:' . implode(',', Sponsor::TIERS)],
            'order' => ['required', 'integer', 'min:0'],
        ]);

        $year = session('active_year', config('parti.active_year', 2026));
        $logoPath = $request->file('logo')->store('sponsors', 'public');

        $sponsor = Sponsor::create([
            'year' => $year,
            'name' => $validated['name'],
            'logo_path' => $logoPath,
            'website_url' => $validated['website_url'],
            'tier' => $validated['tier'],
            'order' => $validated['order'],
            'is_active' => $request->has('is_active'),
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Menambahkan sponsor baru: ' . $sponsor->name . ' (' . $sponsor->tier . ')',
            'entity_type' => 'Sponsor',
            'entity_id' => $sponsor->id,
        ]);

        return redirect()->route('admin.sponsors.index')->with('success', 'Sponsor berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir penyuntingan data sponsor.
     */
    public function edit(Sponsor $sponsor): View
    {
        return view('admin.sponsors.edit', compact('sponsor'));
    }

    /**
     * Memperbarui detail sponsor serta mengganti berkas logo jika diunggah logo baru.
     */
    public function update(Request $request, Sponsor $sponsor): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'tier' => ['required', 'in:' . implode(',', Sponsor::TIERS)],
            'order' => ['required', 'integer', 'min:0'],
        ]);

        $data = [
            'name' => $validated['name'],
            'website_url' => $validated['website_url'],
            'tier' => $validated['tier'],
            'order' => $validated['order'],
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('logo')) {
            if (Storage::disk('public')->exists($sponsor->logo_path)) {
                Storage::disk('public')->delete($sponsor->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('sponsors', 'public');
        }

        $sponsor->update($data);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Mengubah detail sponsor: ' . $sponsor->name,
            'entity_type' => 'Sponsor',
            'entity_id' => $sponsor->id,
        ]);

        return redirect()->route('admin.sponsors.index')->with('success', 'Sponsor berhasil diperbarui.');
    }

    /**
     * Menghapus record sponsor dari database.
     *
     * Catatan audit dibuat terlebih dahulu sebelum penghapusan data agar ID entitas tetap valid saat dicatat.
     */
    public function destroy(Sponsor $sponsor): RedirectResponse
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Menghapus sponsor: ' . $sponsor->name,
            'entity_type' => 'Sponsor',
            'entity_id' => $sponsor->id,
        ]);

        // ponytail: Penghapusan berkas fisik di disk diserahkan penuh ke static deleting boot event di model Sponsor
        $sponsor->delete();

        return redirect()->route('admin.sponsors.index')->with('success', 'Sponsor berhasil dihapus.');
    }
}


