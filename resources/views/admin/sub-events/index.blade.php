@extends('layouts.admin')

@section('title', 'Kelola Sub Acara | PARTI ' . $year)

@section('content')
<div class="space-y-6 text-left">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-display font-bold text-2xl text-ink uppercase tracking-wide">Sub Acara</h1>
            <p class="text-ink-soft text-sm mt-1">Daftar rangkaian sub-acara PARTI pada tahun aktif {{ $year }}.</p>
        </div>
        <a href="{{ route('admin.sub-events.create') }}" class="bg-gradient-to-r from-ember to-ember-dark text-white font-semibold text-xs px-5 py-2.5 rounded-[2px] transition-premium hover:shadow-[0_10px_20px_-5px_rgba(226,101,11,0.4)]">
            + Buat Sub Acara Baru
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white border border-line rounded-[6px] shadow-sm overflow-hidden">
        <div class="p-6 border-b border-line flex justify-between items-center">
            <h3 class="font-display font-bold text-base text-ink uppercase tracking-wide">Rangkaian Acara</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-paper-warm/50 border-b border-line/60 font-mono text-[10px] tracking-wider text-ink-soft/70 uppercase">
                        <th class="px-6 py-4 w-16 text-center">Order</th>
                        <th class="px-6 py-4">Nama Acara</th>
                        <th class="px-6 py-4">Pelaksanaan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Dokumen</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line/40 text-sm">
                    @forelse($subEvents as $subEvent)
                        <tr class="hover:bg-paper-warm/10 transition-colors">
                            <td class="px-6 py-4 text-center font-mono font-bold text-ember">{{ $subEvent->order }}</td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-ink text-base block">{{ $subEvent->name }}</span>
                                <span class="text-xs text-ink-soft/75 italic block mt-0.5">{{ $subEvent->tagline }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs text-ink-soft">
                                @if($subEvent->date_start)
                                    @if($subEvent->date_end && $subEvent->date_start != $subEvent->date_end)
                                        {{ $subEvent->date_start->translatedFormat('j M') }} - {{ $subEvent->date_end->translatedFormat('j M Y') }}
                                    @else
                                        {{ $subEvent->date_start->translatedFormat('j M Y') }}
                                    @endif
                                @else
                                    TBD
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.sub-events.status', $subEvent) }}" class="inline-block">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="appearance-none bg-paper-warm/40 border border-line rounded-[2px] px-2.5 py-1 text-xs font-mono font-bold text-ink cursor-pointer focus:outline-none focus:border-ember">
                                        <option value="DRAFT" @selected($subEvent->status === 'DRAFT') class="text-amber-600 bg-white font-bold">DRAFT</option>
                                        <option value="PUBLISHED" @selected($subEvent->status === 'PUBLISHED') class="text-emerald-600 bg-white font-bold">PUBLISHED</option>
                                        <option value="CLOSED" @selected($subEvent->status === 'CLOSED') class="text-rose-600 bg-white font-bold">CLOSED</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <a href="{{ route('admin.documents.index', $subEvent) }}" class="inline-flex items-center gap-1.5 font-mono text-[11px] text-ink-soft hover:text-ember font-bold uppercase tracking-wider">
                                    📄 Kelola ({{ $subEvent->documents->count() }})
                                </a>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3.5">
                                    <a href="{{ route('admin.sub-events.edit', $subEvent) }}" class="font-mono text-[11px] text-ember hover:text-ember-dark font-bold uppercase tracking-wider">
                                        ✏️ Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.sub-events.destroy', $subEvent) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sub-acara ini?')">
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
                            <td colspan="6" class="px-6 py-8 text-center text-ink-soft/60">
                                Belum ada sub-acara terdaftar untuk tahun {{ $year }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

