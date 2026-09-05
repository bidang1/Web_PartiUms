<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'PARTI ' . (session('active_year', config('parti.active_year', 2026))) . ' | Panel Admin')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;900&family=Cinzel+Decorative:wght@700;900&family=Work+Sans:wght@400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    <!-- Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-theme bg-paper-warm text-ink font-body antialiased overflow-x-hidden" x-data="{ sidebarOpen: false }">
    <!-- Overlay for mobile sidebar -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-ink/30 backdrop-blur-sm md:hidden"></div>

    <!-- Sidebar navigation -->
    <aside class="fixed top-0 bottom-0 left-0 z-50 flex flex-col w-[260px] bg-white border-r border-line/80 shadow-[10px_0_30px_-15px_rgba(28,20,11,0.03)] transform transition-transform duration-300 md:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <!-- Brand Header -->
        <div class="p-6 border-b border-line flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="font-display-decorative font-bold text-[18px] text-ink flex items-center gap-2 hover:opacity-90 transition-opacity">
                <img src="{{ asset('logo.png') }}" alt="Logo PARTI" class="h-6 w-auto">
                <span>PARTI Admin</span>
            </a>
            <button @click="sidebarOpen = false" class="text-ink hover:text-ember md:hidden focus:outline-none" aria-label="Tutup Menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Multi-year Switcher / Badge -->
        <div class="px-6 py-4 border-b border-line/60 bg-paper-warm/40">
            @if(auth()->user() && auth()->user()->role === 'SUPERADMIN')
                <form action="{{ route('admin.change-year') }}" method="POST" class="flex flex-col gap-1.5">
                    @csrf
                    <label for="year_switcher" class="font-mono text-[9px] tracking-widest uppercase text-ink-soft/75 font-bold">Filter Tahun Kerja (Admin)</label>
                    <div class="relative">
                        <select id="year_switcher" name="year" onchange="this.form.submit()" class="w-full appearance-none bg-white border border-line rounded-[2px] px-3 py-1.5 text-xs text-ink font-semibold focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors cursor-pointer">
                            @php
                                $currentYear = session('active_year', config('parti.active_year', 2026));
                                $years = range(2025, 2030);
                            @endphp
                            @foreach($years as $yr)
                                <option value="{{ $yr }}" @selected($yr == $currentYear)>PARTI {{ $yr }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none text-ink-soft/70">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </form>
            @else
                <div class="flex flex-col gap-1">
                    <span class="font-mono text-[9px] tracking-widest uppercase text-ink-soft/75 font-bold">Tahun Aktif</span>
                    <span class="font-mono text-xs font-bold text-ember bg-white border border-line px-3 py-1.5 rounded-[2px]">
                        PARTI {{ session('active_year', config('parti.active_year', 2026)) }}
                    </span>
                </div>
            @endif
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <!-- Common Main Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-[2px] text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-ember/10 text-ember font-semibold' : 'text-ink-soft hover:bg-paper-warm hover:text-ink' }}">
                <span class="text-[16px]">📊</span> Dashboard
            </a>

            @if(auth()->user()->role === 'SUPERADMIN')
                <!-- SUPERADMIN Menu -->
                <a href="{{ route('admin.sub-events.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-[2px] text-sm font-medium transition-colors {{ request()->routeIs('admin.sub-events.*') ? 'bg-ember/10 text-ember font-semibold' : 'text-ink-soft hover:bg-paper-warm hover:text-ink' }}">
                    <span class="text-[16px]">🏆</span> Sub Acara
                </a>
                <a href="{{ route('admin.timeline.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-[2px] text-sm font-medium transition-colors {{ request()->routeIs('admin.timeline.*') ? 'bg-ember/10 text-ember font-semibold' : 'text-ink-soft hover:bg-paper-warm hover:text-ink' }}">
                    <span class="text-[16px]">📅</span> Timeline
                </a>
                <a href="{{ route('admin.sponsors.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-[2px] text-sm font-medium transition-colors {{ request()->routeIs('admin.sponsors.*') ? 'bg-ember/10 text-ember font-semibold' : 'text-ink-soft hover:bg-paper-warm hover:text-ink' }}">
                    <span class="text-[16px]">🤝</span> Sponsor
                </a>
                <a href="{{ route('admin.faqs.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-[2px] text-sm font-medium transition-colors {{ request()->routeIs('admin.faqs.*') ? 'bg-ember/10 text-ember font-semibold' : 'text-ink-soft hover:bg-paper-warm hover:text-ink' }}">
                    <span class="text-[16px]">❓</span> Manajemen FAQ
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-[2px] text-sm font-medium transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-ember/10 text-ember font-semibold' : 'text-ink-soft hover:bg-paper-warm hover:text-ink' }}">
                    <span class="text-[16px]">👤</span> Kesekretariatan
                </a>
                <a href="{{ route('admin.audit-log.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-[2px] text-sm font-medium transition-colors {{ request()->routeIs('admin.audit-log.index') ? 'bg-ember/10 text-ember font-semibold' : 'text-ink-soft hover:bg-paper-warm hover:text-ink' }}">
                    <span class="text-[16px]">📝</span> Audit Log
                </a>
            @endif

            <!-- Common Menu (SUPERADMIN & KESEKRETARIATAN) -->
            <div class="h-[1px] bg-line/60 my-4 mx-3"></div>
            <span class="block px-4 font-mono text-[9px] tracking-widest uppercase text-ink-soft/50 font-bold mb-2">Tugas Harian</span>

            <a href="{{ route('admin.registration-links.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-[2px] text-sm font-medium transition-colors {{ request()->routeIs('admin.registration-links.*') ? 'bg-ember/10 text-ember font-semibold' : 'text-ink-soft hover:bg-paper-warm hover:text-ink' }}">
                <span class="text-[16px]">🔗</span> Link Pendaftaran
            </a>
            <a href="{{ route('admin.change-password') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-[2px] text-sm font-medium transition-colors {{ request()->routeIs('admin.change-password') ? 'bg-ember/10 text-ember font-semibold' : 'text-ink-soft hover:bg-paper-warm hover:text-ink' }}">
                <span class="text-[16px]">🔑</span> Ganti Password
            </a>
        </nav>

        <!-- User Profile Footer -->
        <div class="p-4 border-t border-line bg-paper-warm/30 flex items-center justify-between gap-3">
            <div class="overflow-hidden">
                <span class="block text-xs font-semibold text-ink truncate">{{ auth()->user()->name }}</span>
                <span class="inline-block px-1.5 py-0.5 rounded-[3px] bg-ember/10 border border-ember/20 text-[9px] font-mono font-bold text-ember uppercase mt-0.5">{{ auth()->user()->role }}</span>
            </div>
            
            <form method="POST" action="{{ route('logout') }}" class="inline-block">
                @csrf
                <button type="submit" class="p-2 text-ink-soft hover:text-rose-600 rounded-[2px] transition-colors" title="Keluar" aria-label="Keluar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="md:pl-[260px] min-h-screen flex flex-col">
        <!-- Topbar Header -->
        <header class="h-16 bg-white border-b border-line/80 px-6 flex items-center justify-between sticky top-0 z-30 shadow-[0_2px_20px_-10px_rgba(28,20,11,0.03)] md:shadow-none">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="text-ink hover:text-ember md:hidden focus:outline-none" aria-label="Buka Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h2 class="font-display font-bold text-base tracking-wide text-ink uppercase hidden sm:block">Panel Administrasi</h2>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer" class="font-mono text-[11px] text-ember hover:text-ember-dark font-bold uppercase tracking-wider flex items-center gap-1.5 border border-ember/20 hover:border-ember px-3 py-1.5 rounded-[2px] transition-all bg-ember/5">
                    Lihat Web Publik ↗
                </a>
            </div>
        </header>

        <!-- Dynamic Content Body -->
        <main class="flex-1 p-6 md:p-8">
            <!-- Toast Flash Messages -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-[2px] flex justify-between items-center shadow-sm">
                    <div class="flex items-center gap-2">
                        <span>✅</span>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">✕</button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-sm rounded-[2px] flex justify-between items-center shadow-sm">
                    <div class="flex items-center gap-2">
                        <span>⚠️</span>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-rose-500 hover:text-rose-700">✕</button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>

