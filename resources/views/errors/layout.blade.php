<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ 
          darkMode: localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
      }"
      :class="{ 'dark': darkMode }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val));">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') - @yield('title') | PARTI Himatif UMS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;900&family=Work+Sans:wght@400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper text-ink font-body antialiased min-h-screen flex flex-col justify-between selection:bg-ember selection:text-white transition-colors duration-300">
    <!-- Navbar / Header -->
    <header class="py-6 px-4 sm:px-8 border-b border-line flex items-center justify-between">
        <a href="{{ url('/') }}" class="flex items-center gap-3 group">
            <img src="{{ asset('logo.png') }}" alt="Logo PARTI" class="h-8 w-auto transition-transform group-hover:scale-105">
            <span class="font-display font-bold text-lg tracking-wider text-ink">PARTI <span class="text-ember">2026</span></span>
        </a>
        <button type="button" @click="darkMode = !darkMode" class="p-2 rounded-full border border-line hover:border-ember text-ink-soft transition-colors" aria-label="Ganti Tema">
            <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
            <svg x-show="darkMode" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </button>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center p-6 text-center">
        <div class="max-w-lg w-full space-y-6">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-ember/10 border border-ember/30 text-ember text-3xl font-mono font-bold mx-auto mb-2">
                @yield('code')
            </div>
            
            <h1 class="font-display font-bold text-2xl sm:text-3xl text-ink uppercase tracking-wide">
                @yield('title')
            </h1>
            
            <p class="text-sm sm:text-base text-ink-soft leading-relaxed">
                @yield('message')
            </p>

            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3">
                @yield('actions')
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-6 px-4 text-center text-xs text-ink-soft/60 border-t border-line">
        &copy; {{ date('Y') }} PARTI HIMATIF UMS. Seluruh hak cipta dilindungi undang-undang.
    </footer>
</body>
</html>
