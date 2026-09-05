@extends('layouts.admin')

@section('title', 'Kelola Sponsor | PARTI ' . $year)

@section('content')
<div class="space-y-6 text-left">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-display font-bold text-2xl text-ink uppercase tracking-wide">Sponsor Kegiatan</h1>
            <p class="text-ink-soft text-sm mt-1">Kelola daftar sponsor pendukung event PARTI {{ $year }}.</p>
        </div>
        <a href="{{ route('admin.sponsors.create') }}" class="bg-gradient-to-r from-ember to-ember-dark text-white font-semibold text-xs px-5 py-2.5 rounded-[2px] transition-premium hover:shadow-[0_10px_20px_-5px_rgba(226,101,11,0.4)]">
            + Tambah Sponsor Baru
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white border border-line rounded-[6px] shadow-sm overflow-hidden">
        <div class="p-6 border-b border-line">
            <h3 class="font-display font-bold text-base text-ink uppercase tracking-wide">Daftar Sponsor Terdaftar</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-paper-warm/50 border-b border-line/60 font-mono text-[10px] tracking-wider text-ink-soft/70 uppercase">
                        <th class="px-6 py-4">Logo</th>
                        <th class="px-6 py-4">Nama Perusahaan</th>
                        <th class="px-6 py-4">Tier Sponsor</th>
                        <th class="px-6 py-4 text-center">Urutan Tampil</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line/40 text-sm">
                    @forelse($sponsors as $sponsor)
                        <tr class="hover:bg-paper-warm/10 transition-colors">
                            <td class="px-6 py-4">
                                <div class="h-10 w-24 bg-paper-warm/40 border border-line/40 rounded flex items-center justify-center p-1.5 overflow-hidden">
                                    @if($sponsor->logo_url)
                                        <img src="{{ $sponsor->logo_url }}" alt="{{ $sponsor->name }}" class="max-h-full max-w-full object-contain">
                                    @else
                                        <span class="font-mono text-[9px] text-ink-soft/60 uppercase font-bold text-center">No Logo</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-ink block">{{ $sponsor->name }}</span>
                                @if($sponsor->website_url)
                                    <a href="{{ $sponsor->website_url }}" target="_blank" rel="noopener noreferrer" class="text-xs text-ember hover:underline font-mono truncate max-w-xs block mt-0.5">
                                        {{ $sponsor->website_url }}
                                    </a>
                                @else
                                    <span class="text-xs text-ink-soft/40 italic">Tidak ada website</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-2.5 py-0.5 rounded-[3px] text-[10px] font-mono font-bold uppercase border {{ $sponsor->tier === 'PLATINUM' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($sponsor->tier === 'GOLD' ? 'bg-amber-50 text-amber-700 border-amber-200' : ($sponsor->tier === 'SILVER' ? 'bg-slate-50 text-slate-700 border-slate-200' : 'bg-orange-50 text-orange-700 border-orange-200')) }}">
                                    {{ $sponsor->tier }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-ink-soft">{{ $sponsor->order }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-2 py-0.5 rounded-[3px] text-[10px] font-mono font-bold uppercase border {{ $sponsor->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                                    {{ $sponsor->is_active ? 'Tampil' : 'Sembunyi' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3.5">
                                    <a href="{{ route('admin.sponsors.edit', $sponsor->id) }}" class="font-mono text-[11px] text-ember hover:text-ember-dark font-bold uppercase tracking-wider">
                                        ✏️ Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.sponsors.destroy', $sponsor->id) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sponsor ini?')">
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
                                Belum ada sponsor terdaftar untuk tahun {{ $year }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

