@extends('layouts.admin')

@section('title', 'Ganti Password | PARTI Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 text-left">
    <!-- Breadcrumbs / Back button -->
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.dashboard') }}" class="font-mono text-[11px] text-ink-soft hover:text-ember font-bold uppercase tracking-wider">
            ← Kembali ke Dashboard
        </a>
    </div>

    <!-- Page Title Header -->
    <div>
        <h1 class="font-display font-bold text-2xl text-ink uppercase tracking-wide">Ganti Password</h1>
        <p class="text-ink-soft text-sm mt-1">Ganti password secara berkala untuk menjaga keamanan akun administrasi Anda.</p>
    </div>

    @if(auth()->user()->must_change_password)
        <!-- Alert for forced password change -->
        <div class="p-4 bg-amber-50 border border-amber-200 text-amber-900 text-sm rounded-[2px] space-y-1">
            <p class="font-bold flex items-center gap-1.5">Wajib Mengganti Password:</p>
            <p class="text-xs">Ini adalah login pertama Anda atau password Anda baru saja di-reset oleh Superadmin. Silakan perbarui password Anda sebelum mengakses halaman lain di panel admin.</p>
        </div>
    @endif

    <!-- Change Password Form -->
    <div class="bg-white border border-line rounded-[6px] p-6 md:p-8 shadow-sm">
        <form method="POST" action="{{ route('admin.change-password.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Current Password -->
            <div>
                <label for="current_password" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Password Saat Ini</label>
                <input id="current_password" name="current_password" type="password" required class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" />
                <x-input-error :messages="$errors->get('current_password')" class="mt-2 text-rose-600 text-xs" />
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Password Baru</label>
                <input id="password" name="password" type="password" required class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" />
                <p class="text-[10px] text-ink-soft/60 mt-1">Minimal 8 karakter, pastikan mudah diingat.</p>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-600 text-xs" />
            </div>

            <!-- Confirm New Password -->
            <div>
                <label for="password_confirmation" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Konfirmasi Password Baru</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-rose-600 text-xs" />
            </div>

            <div class="pt-4 border-t border-line flex justify-end">
                <button type="submit" class="bg-gradient-to-r from-ember to-ember-dark text-white font-semibold text-sm px-6 py-2.5 rounded-[2px] transition-premium hover:shadow-[0_10px_20px_-5px_rgba(226,101,11,0.4)] hover:-translate-y-0.5 active:translate-y-0">
                    Simpan Perubahan Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

