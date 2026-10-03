@extends('errors.layout')

@section('code', '403')
@section('title', 'Akses Ditolak')

@section('message', 'Anda tidak memiliki hak akses yang cukup untuk membuka halaman ini. Silakan hubungi Superadmin jika Anda merasa ini adalah kesalahan.')

@section('actions')
    <a href="{{ url('/') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-ember to-ember-dark text-white font-semibold text-xs px-6 py-3 rounded-[3px] transition-all hover:shadow-[0_8px_20px_-4px_rgba(230,81,0,0.4)] hover:-translate-y-0.5 active:translate-y-0 uppercase tracking-wider font-mono">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Kembali ke Beranda</span>
    </a>
    @auth
        <a href="{{ route('admin.dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 border border-line bg-paper-warm/50 hover:bg-paper-warm text-ink text-xs font-semibold px-6 py-3 rounded-[3px] transition-colors uppercase tracking-wider font-mono">
            <span>Panel Admin</span>
        </a>
    @endauth
@endsection
