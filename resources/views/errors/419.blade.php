@extends('errors.layout')

@section('code', '419')
@section('title', 'Sesi Berakhir')

@section('message', 'Sesi pengiriman formulir Anda telah kadaluarsa karena terlalu lama tidak ada aktivitas. Silakan muat ulang halaman ini untuk memperbarui sesi keamanan Anda.')

@section('actions')
    <button type="button" onclick="window.location.reload()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-ember to-ember-dark text-white font-semibold text-xs px-6 py-3 rounded-[3px] transition-all hover:shadow-[0_8px_20px_-4px_rgba(230,81,0,0.4)] hover:-translate-y-0.5 active:translate-y-0 uppercase tracking-wider font-mono cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        <span>Muat Ulang Halaman</span>
    </button>
    <a href="javascript:history.back()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 border border-line bg-paper-warm/50 hover:bg-paper-warm text-ink text-xs font-semibold px-6 py-3 rounded-[3px] transition-colors uppercase tracking-wider font-mono">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Kembali ke Formulir</span>
    </a>
@endsection
