@extends('layouts.admin')

@section('title', 'Dashboard | PARTI ' . $year)

@section('content')
<div class="space-y-8">
    <!-- Header Greeting -->
    <div class="bg-gradient-to-r from-paper to-[#FFFBF4] border border-line rounded-[6px] p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-sm">
        <div>
            <h1 class="font-display font-bold text-2xl text-ink uppercase tracking-wide">Selamat Datang, {{ auth()->user()->name }}!</h1>
            <p class="text-ink-soft text-sm mt-1">Anda masuk sebagai <span class="font-semibold text-ember">{{ auth()->user()->role }}</span> pada panel administrasi PARTI {{ $year }}.</p>
        </div>
        <div class="font-mono text-xs text-ink-soft bg-[#FFF3E5] px-3.5 py-2 border border-ember/10 rounded-[2px] font-semibold whitespace-nowrap">
            Tahun Event Aktif: <span class="text-ember font-bold">{{ $year }}</span>
        </div>
    </div>

    @if(auth()->user()->role === 'SUPERADMIN')
        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <!-- Sub Acara -->
            <a href="{{ route('admin.sub-events.index') }}" class="group bg-white border border-line rounded-[6px] p-6 shadow-[0_4px_12px_rgba(28,20,11,0.02)] transition-premium hover:-translate-y-1 hover:border-ember/50 hover:shadow-[0_15px_30px_-10px_rgba(28,20,11,0.08)] flex flex-col text-left">
                <span class="font-mono text-[10px] tracking-widest uppercase text-ink-soft/75 font-bold mb-1">Sub Acara Terdaftar</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold font-display text-ink group-hover:text-ember transition-colors">{{ $stats['sub_events_count'] }}</span>
                    <span class="text-xs text-ink-soft/60">acara</span>
                </div>
                <span class="text-xs text-ember font-semibold mt-4 flex items-center gap-1">Kelola Sub Acara →</span>
            </a>

            <!-- Timeline -->
            <a href="{{ route('admin.timeline.index') }}" class="group bg-white border border-line rounded-[6px] p-6 shadow-[0_4px_12px_rgba(28,20,11,0.02)] transition-premium hover:-translate-y-1 hover:border-ember/50 hover:shadow-[0_15px_30px_-10px_rgba(28,20,11,0.08)] flex flex-col text-left">
                <span class="font-mono text-[10px] tracking-widest uppercase text-ink-soft/75 font-bold mb-1">Agenda Timeline</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold font-display text-ink group-hover:text-ember transition-colors">{{ $stats['timeline_count'] }}</span>
                    <span class="text-xs text-ink-soft/60">agenda</span>
                </div>
                <span class="text-xs text-ember font-semibold mt-4 flex items-center gap-1">Kelola Timeline →</span>
            </a>

            <!-- Sponsor -->
            <a href="{{ route('admin.sponsors.index') }}" class="group bg-white border border-line rounded-[6px] p-6 shadow-[0_4px_12px_rgba(28,20,11,0.02)] transition-premium hover:-translate-y-1 hover:border-ember/50 hover:shadow-[0_15px_30px_-10px_rgba(28,20,11,0.08)] flex flex-col text-left">
                <span class="font-mono text-[10px] tracking-widest uppercase text-ink-soft/75 font-bold mb-1">Sponsor Terkait</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold font-display text-ink group-hover:text-ember transition-colors">{{ $stats['sponsors_count'] }}</span>
                    <span class="text-xs text-ink-soft/60">perusahaan</span>
                </div>
                <span class="text-xs text-ember font-semibold mt-4 flex items-center gap-1">Kelola Sponsor →</span>
            </a>
        </div>

        <!-- Recent Audit Logs -->
        <div class="bg-white border border-line rounded-[6px] shadow-[0_4px_12px_rgba(28,20,11,0.02)]">
            <div class="p-6 border-b border-line flex justify-between items-center">
                <h3 class="font-display font-bold text-base text-ink uppercase tracking-wide">Aktivitas Terbaru</h3>
                <a href="{{ route('admin.audit-log.index') }}" class="font-mono text-[11px] text-ember hover:text-ember-dark font-bold uppercase tracking-wider">
                    Lihat Semua Log →
                </a>
            </div>
            <div class="divide-y divide-line/60">
                @forelse($recentLogs as $log)
                    <div class="p-5 flex flex-col sm:flex-row justify-between sm:items-center gap-3 text-left">
                        <div class="flex items-start gap-3">
                            <span class="text-lg">📝</span>
                            <div>
                                <p class="text-sm font-semibold text-ink">{{ $log->action }}</p>
                                <p class="text-xs text-ink-soft/70 mt-0.5">Oleh <span class="font-medium">{{ $log->user?->name ?? 'Sistem / Pengguna Dihapus' }}</span> ({{ $log->user?->role ?? '-' }})</p>
                            </div>
                        </div>
                        <span class="font-mono text-[10px] text-ink-soft/60 whitespace-nowrap bg-paper-warm px-2.5 py-1 border border-line/40 rounded-[2px]">
                            {{ $log->created_at->diffForHumans() }}
                        </span>
                    </div>
                @empty
                    <div class="p-8 text-center text-ink-soft/60">
                        Belum ada riwayat aktivitas pada tahun aktif ini.
                    </div>
                @endforelse
            </div>
        </div>

    @else
        <!-- KESEKRETARIATAN Quick Welcome & Guidance -->
        <div class="bg-white border border-line rounded-[6px] p-6 md:p-8 shadow-[0_4px_12px_rgba(28,20,11,0.02)] space-y-6">
            <h3 class="font-display font-bold text-lg text-ink uppercase tracking-wide">Pemberitahuan Tugas</h3>
            <div class="text-sm text-ink-soft leading-relaxed space-y-4">
                <p>Halo, Panitia Kesekretariatan. Tugas utama Anda di sistem ini adalah memperbarui tautan Google Form pendaftaran per sub-acara yang sudah dipersiapkan oleh Superadmin.</p>
                
                <div class="bg-[#FAF6EE] border border-line p-4 rounded-[2px] space-y-2">
                    <p class="font-semibold text-ember-dark flex items-center gap-1.5">📌 Petunjuk Pengisian Link:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Pastikan tautan pendaftaran menggunakan domain resmi Google Form seperti <code class="font-mono bg-white px-1 py-0.5 border border-line rounded">docs.google.com/forms</code> atau <code class="font-mono bg-white px-1 py-0.5 border border-line rounded">forms.gle</code>.</li>
                        <li>Jika link pendaftaran sengaja dikosongkan, tombol pendaftaran di sisi publik otomatis akan berlabel <strong>"Segera Dibuka"</strong>.</li>
                        <li>Sistem secara otomatis akan mencatat riwayat perubahan Anda demi kepentingan koordinasi tim panitia.</li>
                    </ul>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.registration-links.index') }}" class="inline-flex bg-gradient-to-r from-ember to-ember-dark text-white font-semibold text-sm px-6 py-3.5 rounded-[2px] transition-premium hover:shadow-[0_10px_20px_-5px_rgba(226,101,11,0.4)] hover:-translate-y-0.5 active:translate-y-0 text-left">
                    Mulai Update Link Pendaftaran →
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

