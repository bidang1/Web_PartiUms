@extends('layouts.admin')

@section('title', 'Tambah User Kesekretariatan | PARTI Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 text-left">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.users.index') }}" class="font-mono text-[11px] text-ink-soft hover:text-ember font-bold uppercase tracking-wider">
            ← Kembali ke Daftar User
        </a>
    </div>

    <!-- Header -->
    <div>
        <h1 class="font-display font-bold text-2xl text-ink uppercase tracking-wide">Buat Akun Kesekretariatan</h1>
        <p class="text-ink-soft text-sm mt-1">Daftarkan akun panitia baru. Mereka akan diwajibkan memperbarui kata sandi saat login pertama.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white border border-line rounded-[6px] p-6 md:p-8 shadow-sm">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Nama Lengkap Panitia</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-rose-600 text-xs" />
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Email Panitia</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-600 text-xs" />
            </div>

            <!-- Temporary Password -->
            <div>
                <label for="password" class="block font-mono text-[11px] tracking-wider uppercase text-ink-soft mb-1.5 font-bold">Kata Sandi Sementara</label>
                <input id="password" name="password" type="text" value="{{ Str::random(10) }}" required class="block w-full border border-line rounded-[2px] px-3.5 py-2.5 text-sm bg-paper-warm/20 font-mono focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" />
                <p class="text-[10px] text-ink-soft/60 mt-1">Gunakan password acak di atas atau sesuaikan sendiri. Password wajib diganti setelah login pertama.</p>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-600 text-xs" />
            </div>

            <div class="pt-4 border-t border-line flex justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 border border-line rounded-[2px] bg-white text-ink-soft text-sm font-semibold hover:bg-paper-warm/30 transition-colors">
                    Batal
                </a>
                <button type="submit" class="bg-gradient-to-r from-ember to-ember-dark text-white font-semibold text-sm px-6 py-2.5 rounded-[2px] transition-premium hover:shadow-[0_10px_20px_-5px_rgba(226,101,11,0.4)] hover:-translate-y-0.5 active:translate-y-0">
                    Simpan Akun Baru
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

