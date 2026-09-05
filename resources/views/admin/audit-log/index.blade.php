@extends('layouts.admin')

@section('title', 'Log Audit Aktivitas | PARTI Admin')

@section('content')
<div class="space-y-6 text-left">
    <!-- Header -->
    <div>
        <h1 class="font-display font-bold text-2xl text-ink uppercase tracking-wide">Log Audit Aktivitas</h1>
        <p class="text-ink-soft text-sm mt-1">Riwayat aktivitas sensitif dan perubahan data oleh seluruh panitia.</p>
    </div>

    <!-- Filter Bar Card -->
    <div class="bg-white border border-line rounded-[6px] p-6 shadow-sm">
        <form method="GET" action="{{ route('admin.audit-log.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
            <!-- Search -->
            <div>
                <label for="search" class="block font-mono text-[10px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Cari Aktivitas</label>
                <input id="search" name="search" type="text" value="{{ request('search') }}" placeholder="Contoh: update link..." 
                       class="block w-full border border-line rounded-[2px] px-3.5 py-2 text-xs bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" />
            </div>

            <!-- Operator Filter -->
            <div>
                <label for="user_id" class="block font-mono text-[10px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Filter Operator</label>
                <select id="user_id" name="user_id" class="block w-full border border-line rounded-[2px] px-3.5 py-2 text-xs bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember cursor-pointer">
                    <option value="">-- Semua Operator --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }} ({{ $u->role }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-gradient-to-r from-ember to-ember-dark text-white font-semibold text-xs px-4 py-2.5 rounded-[2px] transition-premium hover:shadow-[0_8px_16px_-4px_rgba(226,101,11,0.4)]">
                    Terapkan Filter
                </button>
                <a href="{{ route('admin.audit-log.index') }}" class="px-4 py-2.5 border border-line rounded-[2px] bg-white text-ink-soft text-xs font-semibold hover:bg-paper-warm/30 transition-colors text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white border border-line rounded-[6px] shadow-sm overflow-hidden">
        <div class="p-6 border-b border-line">
            <h3 class="font-display font-bold text-base text-ink uppercase tracking-wide">Riwayat Log</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-paper-warm/50 border-b border-line/60 font-mono text-[10px] tracking-wider text-ink-soft/70 uppercase">
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Operator</th>
                        <th class="px-6 py-4">Aksi Kegiatan</th>
                        <th class="px-6 py-4">Tipe Entitas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line/40 text-sm">
                    @forelse($logs as $log)
                        <tr class="hover:bg-paper-warm/10 transition-colors">
                            <td class="px-6 py-4 font-mono text-xs text-ink-soft whitespace-nowrap">
                                {{ $log->created_at->translatedFormat('j M Y, H:i:s') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-ink block">{{ $log->user?->name ?? 'Pengguna Dihapus / Sistem' }}</span>
                                <span class="inline-block px-1.5 py-0.5 rounded-[2px] bg-ember/5 text-[9px] font-mono font-bold text-ember uppercase mt-0.5">{{ $log->user?->role ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-ink font-medium max-w-lg leading-relaxed">
                                {{ $log->action }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs text-ink-soft/70 bg-paper-warm px-2 py-0.5 rounded-[2px] border border-line/40">
                                    {{ $log->entity_type }} (ID: {{ $log->entity_id }})
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-ink-soft/60">
                                Tidak ada data log yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-6 border-t border-line/60">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

