@extends('layouts.admin')

@section('title', 'Edit Agenda Timeline | PARTI Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 text-left">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.timeline.index') }}" class="font-mono text-[11px] text-ink-soft hover:text-ember font-bold uppercase tracking-wider">
            ← Kembali ke Daftar Timeline
        </a>
    </div>

    <!-- Header -->
    <div>
        <h1 class="font-display font-bold text-2xl text-ink uppercase tracking-wide">Edit Agenda Timeline</h1>
        <p class="text-ink-soft text-sm mt-1">Perbarui informasi agenda "{{ $timeline->title }}" pada alur waktu event PARTI.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white border border-line rounded-[6px] p-6 md:p-8 shadow-sm">
        <form method="POST" action="{{ route('admin.timeline.update', $timeline->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label for="title" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Judul Agenda</label>
                <input id="title" name="title" type="text" value="{{ old('title', $timeline->title) }}" required autofocus class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" placeholder="Contoh: Pembukaan Webinar Nasional" />
                <x-input-error :messages="$errors->get('title')" class="mt-2 text-rose-600 text-xs" />
            </div>

            <!-- Associated Sub Event -->
            <div>
                <label for="sub_event_id" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Kaitkan dengan Sub Acara (Opsional)</label>
                <select id="sub_event_id" name="sub_event_id" class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember cursor-pointer">
                    <option value="">-- Agenda Global (Tidak Terkait Acara Tertentu) --</option>
                    @foreach($subEvents as $sub)
                        <option value="{{ $sub->id }}" @selected(old('sub_event_id', $timeline->sub_event_id) == $sub->id)>{{ $sub->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('sub_event_id')" class="mt-2 text-rose-600 text-xs" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Date -->
                <div>
                    <label for="date" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Tanggal Kegiatan</label>
                    <input id="date" name="date" type="date" value="{{ old('date', $timeline->date ? $timeline->date->format('Y-m-d') : '') }}" required class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" />
                    <x-input-error :messages="$errors->get('date')" class="mt-2 text-rose-600 text-xs" />
                </div>

                <!-- Order -->
                <div>
                    <label for="order" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Urutan Tampil</label>
                    <input id="order" name="order" type="number" value="{{ old('order', $timeline->order) }}" min="0" required class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" />
                    <p class="text-[10px] text-ink-soft/60 mt-1">Digunakan untuk mengurutkan jika ada lebih dari 1 agenda pada tanggal yang sama.</p>
                    <x-input-error :messages="$errors->get('order')" class="mt-2 text-rose-600 text-xs" />
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Deskripsi Singkat</label>
                <textarea id="description" name="description" rows="3" class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors">{{ old('description', $timeline->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2 text-rose-600 text-xs" />
            </div>

            <div class="pt-6 border-t border-line flex justify-end gap-3">
                <a href="{{ route('admin.timeline.index') }}" class="px-5 py-2.5 border border-line rounded-[2px] bg-white text-ink-soft text-sm font-semibold hover:bg-paper-warm/30 transition-colors">
                    Batal
                </a>
                <button type="submit" class="bg-gradient-to-r from-ember to-ember-dark text-white font-semibold text-sm px-6 py-2.5 rounded-[2px] transition-premium hover:shadow-[0_10px_20px_-5px_rgba(226,101,11,0.4)] hover:-translate-y-0.5 active:translate-y-0">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

