@extends('layouts.admin')

@section('title', 'Kelola Timeline | PARTI ' . $year)

@section('content')
<div class="space-y-6 text-left">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-display font-bold text-2xl text-ink uppercase tracking-wide">Timeline Agenda</h1>
            <p class="text-ink-soft text-sm mt-1">Susun jadwal dan alur waktu kegiatan event PARTI {{ $year }}.</p>
        </div>
        <a href="{{ route('admin.timeline.create') }}" class="bg-gradient-to-r from-ember to-ember-dark text-white font-semibold text-xs px-5 py-2.5 rounded-[2px] transition-premium hover:shadow-[0_10px_20px_-5px_rgba(226,101,11,0.4)]">
            + Tambah Agenda Baru
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white border border-line rounded-[6px] shadow-sm overflow-hidden">
        <div class="p-6 border-b border-line">
            <h3 class="font-display font-bold text-base text-ink uppercase tracking-wide">Alur Agenda Kegiatan</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-paper-warm/50 border-b border-line/60 font-mono text-[10px] tracking-wider text-ink-soft/70 uppercase">
                        <th class="px-6 py-4 w-28">Tanggal</th>
                        <th class="px-6 py-4">Agenda / Judul</th>
                        <th class="px-6 py-4">Terkait Sub Acara</th>
                        <th class="px-6 py-4 text-center">Urutan Tampil</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line/40 text-sm">
                    @forelse($timeline as $item)
                        <tr class="hover:bg-paper-warm/10 transition-colors">
                            <td class="px-6 py-4 font-semibold text-ink whitespace-nowrap">
                                {{ $item->date ? $item->date->translatedFormat('d M Y') : 'TBD' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-ink block">{{ $item->title }}</span>
                                <span class="text-xs text-ink-soft/80 block mt-0.5 max-w-md">{{ $item->description }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->subEvent)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-[3px] bg-ember/5 border border-ember/20 text-xs text-ember font-bold">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                        </svg>
                                        <span>{{ $item->subEvent->name }}</span>
                                    </span>
                                @else
                                    <span class="text-xs text-ink-soft/40 italic">Agenda Global</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-ink-soft">{{ $item->order }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3.5">
                                    <a href="{{ route('admin.timeline.edit', $item->id) }}" class="font-mono text-[11px] text-ember hover:text-ember-dark font-bold uppercase tracking-wider inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <span>Edit</span>
                                    </a>
                                    <form method="POST" action="{{ route('admin.timeline.destroy', $item->id) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus agenda ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-mono text-[11px] text-rose-600 hover:text-rose-800 font-bold uppercase tracking-wider inline-flex items-center gap-1 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-ink-soft/60">
                                Belum ada agenda timeline terdaftar untuk tahun {{ $year }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

