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
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-ember shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
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

        <!-- Server Maintenance Tools (Superadmin) -->
        <div class="bg-white border border-line rounded-[6px] shadow-[0_4px_12px_rgba(28,20,11,0.02)]">
            <div class="p-6 border-b border-line">
                <h3 class="font-display font-bold text-base text-ink uppercase tracking-wide">Pemeliharaan Server & Database</h3>
                <p class="text-xs text-ink-soft/70 mt-1">Jalankan perintah pemeliharaan langsung setelah deploy kode baru ke hosting.</p>
            </div>
            <div class="p-6 flex flex-wrap items-center gap-3">
                <form method="POST" action="{{ route('admin.maintenance.migrate') }}" onsubmit="return confirm('Jalankan database migration sekarang?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 text-xs font-semibold px-4 py-2.5 rounded-[3px] border border-ember/30 bg-ember/10 text-ember-dark hover:bg-ember hover:text-white transition-colors cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                        </svg>
                        <span>Jalankan Database Migration</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.maintenance.clear-cache') }}" onsubmit="return confirm('Bersihkan seluruh cache aplikasi?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 text-xs font-semibold px-4 py-2.5 rounded-[3px] border border-line bg-paper-warm/40 text-ink hover:bg-paper-warm hover:border-ink/20 transition-colors cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Bersihkan Cache</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.maintenance.symlink') }}" onsubmit="return confirm('Buat storage link symlink?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 text-xs font-semibold px-4 py-2.5 rounded-[3px] border border-line bg-paper-warm/40 text-ink hover:bg-paper-warm hover:border-ink/20 transition-colors cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                        <span>Buat Storage Link</span>
                    </button>
                </form>
            </div>
        </div>

    @else
        <!-- KESEKRETARIATAN Quick Welcome & Guidance -->
        <div class="bg-white border border-line rounded-[6px] p-6 md:p-8 shadow-[0_4px_12px_rgba(28,20,11,0.02)] space-y-6">
            <h3 class="font-display font-bold text-lg text-ink uppercase tracking-wide">Pemberitahuan Tugas</h3>
            <div class="text-sm text-ink-soft leading-relaxed space-y-4">
                <p>Halo, Panitia Kesekretariatan. Tugas utama Anda di sistem ini adalah memperbarui tautan Google Form pendaftaran per sub-acara yang sudah dipersiapkan oleh Superadmin.</p>
                
                <div class="bg-[#FAF6EE] border border-line p-4 rounded-[2px] space-y-2">
                    <p class="font-semibold text-ember-dark flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-ember" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Petunjuk Pengisian Link:</span>
                    </p>
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

