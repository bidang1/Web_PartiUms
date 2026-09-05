@extends('layouts.admin')

@section('title', 'Buat Sub Acara Baru | PARTI Admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 text-left">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.sub-events.index') }}" class="font-mono text-[11px] text-ink-soft hover:text-ember font-bold uppercase tracking-wider">
            ← Kembali ke Daftar Sub Acara
        </a>
    </div>

    <!-- Header -->
    <div>
        <h1 class="font-display font-bold text-2xl text-ink uppercase tracking-wide">Buat Sub Acara Baru</h1>
        <p class="text-ink-soft text-sm mt-1">Tambahkan sub-acara baru dalam rangkaian event PARTI tahun ini.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white border border-line rounded-[6px] p-6 md:p-8 shadow-sm">
        <form method="POST" action="{{ route('admin.sub-events.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Nama Acara</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-rose-600 text-xs" />
                </div>

                <!-- Tagline -->
                <div class="md:col-span-2">
                    <label for="tagline" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Tagline Singkat</label>
                    <input id="tagline" name="tagline" type="text" value="{{ old('tagline') }}" class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" placeholder="Contoh: Digital Renaissance Era" />
                    <x-input-error :messages="$errors->get('tagline')" class="mt-2 text-rose-600 text-xs" />
                </div>

                <!-- Date Start -->
                <div>
                    <label for="date_start" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Tanggal Mulai</label>
                    <input id="date_start" name="date_start" type="date" value="{{ old('date_start') }}" class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" />
                    <x-input-error :messages="$errors->get('date_start')" class="mt-2 text-rose-600 text-xs" />
                </div>

                <!-- Date End -->
                <div>
                    <label for="date_end" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Tanggal Selesai</label>
                    <input id="date_end" name="date_end" type="date" value="{{ old('date_end') }}" class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" />
                    <x-input-error :messages="$errors->get('date_end')" class="mt-2 text-rose-600 text-xs" />
                </div>

                <!-- Type (ONLINE/OFFLINE/HYBRID) -->
                <div>
                    <label for="type" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Pelaksanaan Acara</label>
                    <select id="type" name="type" required class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember cursor-pointer">
                        <option value="OFFLINE" @selected(old('type') === 'OFFLINE')>OFFLINE (Tatap Muka)</option>
                        <option value="ONLINE" @selected(old('type') === 'ONLINE')>ONLINE (Daring)</option>
                        <option value="HYBRID" @selected(old('type') === 'HYBRID')>HYBRID (Kombinasi)</option>
                    </select>
                    <x-input-error :messages="$errors->get('type')" class="mt-2 text-rose-600 text-xs" />
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Lokasi / Tempat Acara</label>
                    <input id="location" name="location" type="text" value="{{ old('location') }}" placeholder="Contoh: Gedung J UMS atau Zoom Meeting" class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" />
                    <x-input-error :messages="$errors->get('location')" class="mt-2 text-rose-600 text-xs" />
                </div>

                <!-- PJ Names -->
                <div class="md:col-span-2">
                    <label for="pj_names" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Nama PJ (Penanggung Jawab)</label>
                    <input id="pj_names" name="pj_names" type="text" value="{{ old('pj_names') }}" class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" placeholder="Pisahkan dengan koma, contoh: Rizqi, Tasya" />
                    <x-input-error :messages="$errors->get('pj_names')" class="mt-2 text-rose-600 text-xs" />
                </div>

                <!-- HTM Tiers -->
                <div class="md:col-span-2">
                    <label for="htm_tiers" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Kategori HTM (Tiket Masuk)</label>
                    <textarea id="htm_tiers" name="htm_tiers" rows="3" class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" placeholder="Format: NamaKategori:Harga (satu per baris)&#10;Contoh:&#10;Umum:50000&#10;VIP:100000">{{ old('htm_tiers') }}</textarea>
                    <p class="text-[10px] text-ink-soft/60 mt-1">Kosongkan jika acara gratis. Gunakan format yang tepat agar harga tampil di halaman detail publik.</p>
                    <x-input-error :messages="$errors->get('htm_tiers')" class="mt-2 text-rose-600 text-xs" />
                </div>

                <!-- Poster -->
                <div class="md:col-span-2">
                    <label for="poster" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Poster / Banner Acara</label>
                    <input id="poster" name="poster" type="file" accept="image/*" class="block w-full border border-line rounded-[2px] px-3.5 py-2 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" />
                    <p class="text-[10px] text-ink-soft/60 mt-1">Format gambar (JPEG, PNG, JPG, WEBP). Maksimal 5MB. Gambar ini akan digunakan untuk pratinjau sharing di media sosial.</p>
                    <x-input-error :messages="$errors->get('poster')" class="mt-2 text-rose-600 text-xs" />
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Deskripsi Sub Acara</label>
                    <textarea id="description" name="description" rows="5" class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2 text-rose-600 text-xs" />
                </div>

                <!-- Order -->
                <div>
                    <label for="order" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Nomor Urutan Tampil</label>
                    <input id="order" name="order" type="number" value="{{ old('order', 0) }}" min="0" required class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" />
                    <p class="text-[10px] text-ink-soft/60 mt-1">Angka lebih kecil tampil pertama.</p>
                    <x-input-error :messages="$errors->get('order')" class="mt-2 text-rose-600 text-xs" />
                </div>
            </div>

            <div class="pt-6 border-t border-line flex justify-end gap-3">
                <a href="{{ route('admin.sub-events.index') }}" class="px-5 py-2.5 border border-line rounded-[2px] bg-white text-ink-soft text-sm font-semibold hover:bg-paper-warm/30 transition-colors">
                    Batal
                </a>
                <button type="submit" class="bg-gradient-to-r from-ember to-ember-dark text-white font-semibold text-sm px-6 py-2.5 rounded-[2px] transition-premium hover:shadow-[0_10px_20px_-5px_rgba(226,101,11,0.4)] hover:-translate-y-0.5 active:translate-y-0">
                    Simpan Sub Acara
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

