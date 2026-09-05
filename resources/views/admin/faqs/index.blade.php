@extends('layouts.admin')

@section('title', 'Manajemen FAQ | PARTI ' . (session('active_year', config('parti.active_year', 2026))))

@section('content')
<div class="space-y-6 text-left">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-display font-bold text-2xl text-ink uppercase tracking-wide">Manajemen FAQ</h1>
            <p class="text-ink-soft text-sm mt-1">Kelola daftar pertanyaan yang sering diajukan di halaman utama.</p>
        </div>
        <a href="{{ route('admin.faqs.create') }}" class="bg-gradient-to-r from-ember to-ember-dark text-white font-semibold text-xs px-5 py-2.5 rounded-[2px] transition-premium hover:shadow-[0_10px_20px_-5px_rgba(226,101,11,0.4)]">
            + Tambah FAQ Baru
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white border border-line rounded-[6px] shadow-sm overflow-hidden">
        <div class="p-6 border-b border-line">
            <h3 class="font-display font-bold text-base text-ink uppercase tracking-wide">Daftar FAQ Terdaftar</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-paper-warm/50 border-b border-line/60 font-mono text-[10px] tracking-wider text-ink-soft/70 uppercase">
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Pertanyaan & Jawaban</th>
                        <th class="px-6 py-4 text-center">Urutan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line/40 text-sm">
                    @forelse($faqs as $faq)
                        <tr class="hover:bg-paper-warm/10 transition-colors">
                            <td class="px-6 py-4">
                                <span class="inline-block px-2.5 py-0.5 rounded-[3px] text-[10px] font-mono font-bold uppercase border bg-slate-50 text-slate-700 border-slate-200">
                                    {{ $faq->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-ink block mb-1">{{ $faq->question }}</span>
                                <span class="text-xs text-ink-soft/70 block max-w-lg line-clamp-2">
                                    {{ $faq->answer }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-ink-soft">{{ $faq->order }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-2 py-0.5 rounded-[3px] text-[10px] font-mono font-bold uppercase border {{ $faq->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                                    {{ $faq->is_active ? 'Tampil' : 'Sembunyi' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3.5">
                                    <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="font-mono text-[11px] text-ember hover:text-ember-dark font-bold uppercase tracking-wider">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.faqs.destroy', $faq->id) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus FAQ ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-mono text-[11px] text-rose-600 hover:text-rose-800 font-bold uppercase tracking-wider">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-ink-soft/60">
                                Belum ada FAQ terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
