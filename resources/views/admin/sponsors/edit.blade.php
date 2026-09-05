@extends('layouts.admin')

@section('title', 'Edit Sponsor | PARTI Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 text-left">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.sponsors.index') }}" class="font-mono text-[11px] text-ink-soft hover:text-ember font-bold uppercase tracking-wider">
            ← Kembali ke Daftar Sponsor
        </a>
    </div>

    <!-- Header -->
    <div>
        <h1 class="font-display font-bold text-2xl text-ink uppercase tracking-wide">Edit Sponsor</h1>
        <p class="text-ink-soft text-sm mt-1">Perbarui informasi logo perusahaan sponsor pendukung event PARTI.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white border border-line rounded-[6px] p-6 md:p-8 shadow-sm">
        <form method="POST" action="{{ route('admin.sponsors.update', $sponsor->id) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Nama Perusahaan / Sponsor</label>
                <input id="name" name="name" type="text" value="{{ old('name', $sponsor->name) }}" required autofocus class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" placeholder="Contoh: PT. Sumber Makmur" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-rose-600 text-xs" />
            </div>

            <!-- Tier Sponsor -->
            <div>
                <label for="tier" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Tier Sponsor</label>
                <select id="tier" name="tier" class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember cursor-pointer">
                    <option value="PLATINUM" @selected(old('tier', $sponsor->tier) === 'PLATINUM')>PLATINUM</option>
                    <option value="GOLD" @selected(old('tier', $sponsor->tier) === 'GOLD')>GOLD</option>
                    <option value="SILVER" @selected(old('tier', $sponsor->tier) === 'SILVER')>SILVER</option>
                    <option value="BRONZE" @selected(old('tier', $sponsor->tier) === 'BRONZE')>BRONZE</option>
                </select>
                <x-input-error :messages="$errors->get('tier')" class="mt-2 text-rose-600 text-xs" />
            </div>

            <!-- Logo File (Optional on Edit) -->
            <div class="space-y-3">
                <label for="logo" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft font-bold">File Logo Sponsor (Pilih hanya jika ingin diganti)</label>
                
                <!-- Current logo preview -->
                <div class="h-16 w-36 bg-paper-warm/40 border border-line/40 rounded flex items-center justify-center p-2 overflow-hidden">
                    <img src="{{ $sponsor->logo_url }}" alt="Logo Saat Ini" class="max-h-full max-w-full object-contain">
                </div>

                <input id="logo" name="logo" type="file" class="block w-full text-xs text-ink-soft file:mr-3 file:py-1.5 file:px-3 file:rounded-[2px] file:border file:border-line file:text-xs file:font-semibold file:bg-paper-warm/40 file:text-ink-soft hover:file:bg-paper-warm cursor-pointer" />
                <p class="text-[9px] text-ink-soft/60 mt-1">Gunakan file gambar format PNG/JPG dengan background transparan, maksimal 2 MB.</p>
                <x-input-error :messages="$errors->get('logo')" class="mt-2 text-rose-600 text-xs" />
            </div>

            <!-- Website URL -->
            <div>
                <label for="website_url" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Link Website (Opsional)</label>
                <input id="website_url" name="website_url" type="url" value="{{ old('website_url', $sponsor->website_url) }}" class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" placeholder="https://example.com" />
                <x-input-error :messages="$errors->get('website_url')" class="mt-2 text-rose-600 text-xs" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Order -->
                <div>
                    <label for="order" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Urutan Tampil</label>
                    <input id="order" name="order" type="number" value="{{ old('order', $sponsor->order) }}" min="0" required class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" />
                    <p class="text-[10px] text-ink-soft/60 mt-1">Angka lebih kecil tampil pertama dalam satu tier.</p>
                    <x-input-error :messages="$errors->get('order')" class="mt-2 text-rose-600 text-xs" />
                </div>

                <!-- Status Checkbox -->
                <div class="flex items-center pt-6">
                    <label for="is_active" class="inline-flex items-center text-sm text-ink cursor-pointer">
                        <input id="is_active" type="checkbox" name="is_active" value="1" @checked(old('is_active', $sponsor->is_active)) class="rounded border-line text-ember focus:ring-ember">
                        <span class="ms-2 font-medium">Tampilkan di Halaman Publik</span>
                    </label>
                </div>
            </div>

            <div class="pt-6 border-t border-line flex justify-end gap-3">
                <a href="{{ route('admin.sponsors.index') }}" class="px-5 py-2.5 border border-line rounded-[2px] bg-white text-ink-soft text-sm font-semibold hover:bg-paper-warm/30 transition-colors">
                    Batal
                </a>
                <button type="submit" class="bg-gradient-to-r from-ember to-ember-dark text-white font-semibold text-sm px-6 py-2.5 rounded-[2px] transition-premium hover:shadow-[0_10px_20px_-5px_rgba(226,101,11,0.4)] hover:-translate-y-0.5 active:translate-y-0">
                    Simpan Sponsor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

