@extends('layouts.admin')

@section('title', 'Manajemen User Kesekretariatan | PARTI Admin')

@section('content')
<div class="space-y-6 text-left">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-display font-bold text-2xl text-ink uppercase tracking-wide">User Kesekretariatan</h1>
            <p class="text-ink-soft text-sm mt-1">Kelola hak akses panitia Kesekretariatan untuk memperbarui link Google Form.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-gradient-to-r from-ember to-ember-dark text-white font-semibold text-xs px-5 py-2.5 rounded-[2px] transition-premium hover:shadow-[0_10px_20px_-5px_rgba(226,101,11,0.4)]">
            + Tambah User Baru
        </a>
    </div>

    <!-- Users Table Card -->
    <div class="bg-white border border-line rounded-[6px] shadow-sm overflow-hidden" x-data="{ activeResetId: null }">
        <div class="p-6 border-b border-line">
            <h3 class="font-display font-bold text-base text-ink uppercase tracking-wide">Daftar Panitia</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-paper-warm/50 border-b border-line/60 font-mono text-[10px] tracking-wider text-ink-soft/70 uppercase">
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4">Dibuat Pada</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line/40 text-sm">
                    @forelse($users as $user)
                        <tr class="hover:bg-paper-warm/10 transition-colors">
                            <td class="px-6 py-4 font-semibold text-ink">{{ $user->name }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-ink-soft">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-2 py-0.5 rounded-[3px] text-[10px] font-mono font-bold uppercase border {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-ink-soft/75">{{ $user->created_at->translatedFormat('j M Y, H:i') }}</td>
                            <td class="px-6 py-4 text-right space-y-2">
                                <div class="flex items-center justify-end gap-3 flex-wrap">
                                    <!-- Reset Password Trigger -->
                                    <button @click="activeResetId = (activeResetId === {{ $user->id }} ? null : {{ $user->id }})" class="font-mono text-[11px] text-ember hover:text-ember-dark font-bold uppercase tracking-wider">
                                        🔑 Reset Sandi
                                    </button>

                                    <!-- Status Toggle Form -->
                                    @if($user->is_active)
                                        <form method="POST" action="{{ route('admin.users.deactivate', $user->id) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan akun ini?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="font-mono text-[11px] text-rose-600 hover:text-rose-800 font-bold uppercase tracking-wider">
                                                🛑 Nonaktifkan
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.activate', $user->id) }}" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="font-mono text-[11px] text-emerald-600 hover:text-emerald-800 font-bold uppercase tracking-wider">
                                                ✅ Aktifkan
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <!-- Collapsible Reset Password Form Panel -->
                                <div x-show="activeResetId === {{ $user->id }}" x-cloak x-collapse class="mt-4 p-4 border border-line bg-paper-warm/40 rounded-[2px] text-left max-w-sm ml-auto">
                                    <form method="POST" action="{{ route('admin.users.reset-password', $user->id) }}" class="space-y-3">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label for="pass_{{ $user->id }}" class="block font-mono text-[9px] tracking-wider uppercase text-ink-soft mb-1 font-bold">Password Baru</label>
                                            <input id="pass_{{ $user->id }}" type="password" name="password" required placeholder="Minimal 8 karakter"
                                                   class="w-full border border-line rounded-[2px] px-2.5 py-1.5 text-xs bg-white focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" />
                                        </div>
                                        <div class="flex justify-end gap-2 text-xs">
                                            <button type="button" @click="activeResetId = null" class="px-3 py-1.5 border border-line rounded-[2px] bg-white text-ink-soft hover:bg-paper-warm/30">
                                                Batal
                                            </button>
                                            <button type="submit" class="px-3 py-1.5 bg-ember hover:bg-ember-dark text-white rounded-[2px] font-semibold">
                                                Reset Sandi
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-ink-soft/60">
                                Belum ada panitia kesekretariatan terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-6 border-t border-line/60">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

