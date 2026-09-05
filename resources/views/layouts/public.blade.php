@php
    // Data statis lembaga & organisasi penyelenggara event (UMS, Prodi TI, dan HIMATIF)
    $organizers = [
        [
            'id' => 'ums',
            'nama' => 'UMS',
            'full_name' => 'Universitas Muhammadiyah Surakarta',
            'logo' => asset('images/logo-ums.png'),
            'logo_light' => asset('images/logo-ums.png'),
            'url' => 'https://www.ums.ac.id/'
        ],
        [
            'id' => 'ti',
            'nama' => 'TEKNIK INFORMATIKA UMS',
            'full_name' => 'Program Studi Teknik Informatika UMS',
            'logo' => asset('images/logo-tf.png'),
            'logo_light' => asset('images/logo-tf.png'),
            'url' => 'https://teknikinformatika.ums.ac.id/'
        ],
        [
            'id' => 'himatif',
            'nama' => 'HIMATIF',
            'full_name' => 'HIMATIF UMS',
            'logo' => asset('images/logo-himatif.png'),
            'logo_light' => asset('images/logo-himatif-light.png'),
            'url' => 'https://himatifums.org/'
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ 
          darkMode: localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
          canInstallPwa: false,
          deferredPrompt: null,
          initPwa() {
              window.addEventListener('beforeinstallprompt', (e) => {
                  e.preventDefault();
                  this.deferredPrompt = e;
                  this.canInstallPwa = true;
              });
              window.addEventListener('appinstalled', () => {
                  this.deferredPrompt = null;
                  this.canInstallPwa = false;
              });
          },
          async installPwa() {
              if (!this.deferredPrompt) return;
              this.deferredPrompt.prompt();
              const { outcome } = await this.deferredPrompt.userChoice;
              if (outcome === 'accepted') {
                  this.canInstallPwa = false;
              }
              this.deferredPrompt = null;
          }
      }"
      :class="{ 'dark': darkMode }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val)); initPwa();">
<head>
    <!--
      PARTI 2026 - Parade Teknik Informatika
      Build: v1.0.0 (Codename: Vanguard)
      HIMATIF Universitas Muhammadiyah Surakarta
      Engineered & Crafted by AtnanLabs (https://www.atnan.my.id/)
    -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'PARTI Himatif UMS')</title>
    <meta name="description" content="@yield('meta_description', 'Website PARTI Himatif UMS, platform informasi dan pendaftaran rangkaian acara HIMATIF UMS.')">
    <meta name="keywords" content="@yield('meta_keywords', 'PARTI 2026, Parade Teknik Informatika, HIMATIF UMS, lomba informatika, kompetisi IT, seminar IT, festival teknologi, Surakarta, Universitas Muhammadiyah Surakarta')">

    <!-- Canonical Link -->
    <link rel="canonical" href="{{ request()->url() }}">

    <!-- DNS Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Open Graph / Facebook SEO -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:site_name" content="PARTI Himatif UMS">
    <meta property="og:title" content="@yield('og_title', 'PARTI Himatif UMS')">
    <meta property="og:description" content="@yield('og_description', 'Website PARTI Himatif UMS, platform informasi dan pendaftaran rangkaian acara HIMATIF UMS.')">
    <meta property="og:image" content="@yield('og_image', asset('logo.png'))">

    <!-- Twitter SEO -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ request()->url() }}">
    <meta property="twitter:title" content="@yield('og_title', 'PARTI Himatif UMS')">
    <meta property="twitter:description" content="@yield('og_description', 'Website PARTI Himatif UMS, platform informasi dan pendaftaran rangkaian acara HIMATIF UMS.')">
    <meta property="twitter:image" content="@yield('og_image', asset('logo.png'))">
    @if(config('parti.seo.twitter_handle'))
    <meta property="twitter:site" content="{{ config('parti.seo.twitter_handle') }}">
    @endif

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#0c0d0e">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="PARTI 2026">
    <link rel="apple-touch-icon" href="{{ asset('icon-192.png') }}">

    <!-- AEO & GEO Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {!! json_encode([
      '@context' => 'https://schema.org',
      '@graph' => [
        [
          '@type' => 'EducationalOrganization',
          '@id' => url('/') . '#organization',
          'name' => 'HIMATIF UMS',
          'alternateName' => 'Himpunan Mahasiswa Teknik Informatika UMS',
          'url' => 'https://himatifums.org/',
          'logo' => asset('images/logo-himatif.png'),
          'sameAs' => array_values(array_filter([
            'https://www.ums.ac.id/',
            'https://teknikinformatika.ums.ac.id/',
            config('parti.socials.parti.instagram'),
            config('parti.socials.parti.tiktok'),
            config('parti.socials.himatif.instagram'),
          ]))
        ],
        [
          '@type' => 'WebSite',
          '@id' => url('/') . '#website',
          'url' => url('/'),
          'name' => 'PARTI ' . session('active_year', config('parti.active_year', 2026)),
          'description' => 'Parade Teknik Informatika HIMATIF Universitas Muhammadiyah Surakarta',
          'publisher' => [
            '@id' => url('/') . '#organization'
          ],
          'inLanguage' => 'id-ID'
        ]
      ]
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @yield('structured_data')
    @yield('breadcrumb_data')

    <!-- Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{
          isPageLoaded: false,
          showSplash: false,
          logoVisible: false,
          logoExiting: false,
          progress: 0,
          _interval: null,
          _done: false,
          init() {
              const alreadySeen = (function() {
                  try { return sessionStorage.getItem('parti_splash_seen'); } catch (e) { return null; }
              })();

              if (alreadySeen) {
                  this.isPageLoaded = true;
                  this.showSplash = false;
                  return;
              }

              this.showSplash = true;
              this.startLoading();

              // Failsafe timeout to prevent any freeze
              setTimeout(() => {
                  if (!this._done) this.finishLoading();
              }, 1600);
          },
          startLoading() {
              this._interval = setInterval(() => {
                  if (this.progress < 90) {
                      this.progress = Math.min(90, this.progress + (90 - this.progress) * 0.15);
                  }
              }, 40);
              if (document.readyState === 'complete') {
                  this.finishLoading();
              } else {
                  window.addEventListener('load', () => this.finishLoading());
              }
          },
          finishLoading() {
              if (this._done) return;
              this._done = true;
              clearInterval(this._interval);
              this.progress = 100;
              try { sessionStorage.setItem('parti_splash_seen', '1'); } catch (e) {}

              setTimeout(() => { this.logoVisible = true; }, 150);
              setTimeout(() => { this.logoExiting = true; }, 700);
              setTimeout(() => { this.isPageLoaded = true; }, 900);
              setTimeout(() => { this.showSplash = false; }, 1200);
          }
      }"
      class="bg-paper text-ink font-body antialiased overflow-x-hidden mac-aurora-bg min-h-screen flex flex-col transition-colors duration-500">

    <!-- macOS style Splash Screen Loader -->
    <div x-show="showSplash" 
         x-cloak
         class="fixed inset-0 z-[100] bg-paper transition-opacity duration-700 ease-premium"
         :class="isPageLoaded ? 'opacity-0 pointer-events-none' : 'opacity-100'">

        <!-- Center Logo & Minimal Progress Ring -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center gap-5 z-10 pointer-events-none">
            <div class="reveal-logo transition-all duration-700 ease-premium"
                 :class="logoExiting 
                     ? 'opacity-0 scale-[0.94] blur-md' 
                     : (logoVisible ? 'reveal-logo-visible' : 'reveal-logo-hidden')">
                <img src="{{ asset('logo.png') }}" alt="Logo PARTI" 
                     class="h-16 w-auto drop-shadow-sm">
            </div>

            <div class="flex flex-col items-center gap-1.5 reveal-text transition-all duration-700 ease-premium"
                 :class="logoExiting 
                     ? 'opacity-0 scale-[0.96] blur-sm' 
                     : (logoVisible ? 'reveal-text-visible' : 'reveal-text-hidden')">
                <span class="font-display font-bold text-[18px] md:text-[22px] tracking-[0.15em] text-ink uppercase whitespace-nowrap">PARTI {{ session('active_year', config('parti.active_year', 2026)) }}</span>
                <div class="flex items-center gap-2 mt-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-ember animate-ping"></span>
                    <span class="font-mono text-[9px] tracking-wider text-ink-soft/60 uppercase">System Initializing</span>
                </div>
            </div>
        </div>
    </div>

    <!-- macOS Full-Width Edge-to-Edge Header Navbar -->
    <header class="sticky top-0 z-50 w-full bg-paper/90 dark:bg-paper-warm/90 backdrop-blur-xl border-b border-line shadow-sm transition-all duration-[1000ms] ease-premium transform"
            :class="isPageLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-4'">
        
        <!-- Strip Top Bar Logo Organisasi Penyelenggara -->
        <div class="w-full border-b border-line/40 bg-black/[0.03] dark:bg-white/[0.02] py-2 px-4 md:px-8 overflow-hidden">
            <div class="flex items-center justify-between gap-4">
                <!-- Label Statis di Sisi Kiri -->
                <div class="flex items-center gap-2 shrink-0 z-10 bg-paper/90 dark:bg-paper-warm/90 pr-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-ember inline-block"></span>
                    <span class="font-mono text-[9px] sm:text-[9.5px] tracking-[0.2em] uppercase text-ink-soft/80 font-bold whitespace-nowrap">
                        DISELENGGARAKAN OLEH
                    </span>
                </div>
                
                <!-- Marquee Loop Running Logos Saja di Sisi Kanan -->
                <div class="logoloop-container logoloop-fade-mask py-0.5 flex-1">
                    <div class="inline-flex items-center gap-6 sm:gap-8 animate-logoloop-left-fast whitespace-nowrap">
                        <!-- Jalur 1 (Trek Asli Berulang 4x agar Padat Tanpa Celah) -->
                        <div class="flex items-center gap-6 sm:gap-8 shrink-0">
                            @for($i = 0; $i < 4; $i++)
                                @foreach($organizers as $org)
                                <a href="{{ $org['url'] }}" target="_blank" rel="noopener noreferrer" 
                                   class="group flex items-center gap-1.5 sm:gap-2 transition-all duration-300"
                                   title="{{ $org['full_name'] }}">
                                    <img src="{{ $org['logo'] }}" alt="{{ $org['nama'] }}" 
                                         x-show="darkMode"
                                         class="h-5 sm:h-6 w-auto object-contain filter grayscale brightness-125 opacity-80 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-300">
                                    <img src="{{ $org['logo_light'] }}" alt="{{ $org['nama'] }}" 
                                         x-show="!darkMode" x-cloak
                                         class="h-5 sm:h-6 w-auto object-contain filter opacity-85 group-hover:opacity-100 transition-all duration-300">
                                    <span class="font-mono text-[9px] sm:text-[10px] tracking-wider uppercase text-ink-soft/80 group-hover:text-ember transition-colors font-bold whitespace-nowrap">
                                        {{ $org['nama'] }}
                                    </span>
                                </a>
                                <span class="h-3 w-[1px] bg-line/50 inline-block"></span>
                                @endforeach
                            @endfor
                        </div>

                        <!-- Jalur 2 (Pengulangan Animasi Marquee Infinite Berulang 4x) -->
                        <div class="flex items-center gap-6 sm:gap-8 shrink-0" aria-hidden="true">
                            @for($i = 0; $i < 4; $i++)
                                @foreach($organizers as $org)
                                <a href="{{ $org['url'] }}" target="_blank" rel="noopener noreferrer" 
                                   class="group flex items-center gap-1.5 sm:gap-2 transition-all duration-300"
                                   title="{{ $org['full_name'] }}">
                                    <img src="{{ $org['logo'] }}" alt="{{ $org['nama'] }}" 
                                         x-show="darkMode"
                                         class="h-5 sm:h-6 w-auto object-contain filter grayscale brightness-125 opacity-80 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-300">
                                    <img src="{{ $org['logo_light'] }}" alt="{{ $org['nama'] }}" 
                                         x-show="!darkMode" x-cloak
                                         class="h-5 sm:h-6 w-auto object-contain filter opacity-85 group-hover:opacity-100 transition-all duration-300">
                                    <span class="font-mono text-[9px] sm:text-[10px] tracking-wider uppercase text-ink-soft/80 group-hover:text-ember transition-colors font-bold whitespace-nowrap">
                                        {{ $org['nama'] }}
                                    </span>
                                </a>
                                <span class="h-3 w-[1px] bg-line/50 inline-block"></span>
                                @endforeach
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <nav x-data="{ mobileMenuOpen: false }" 
             class="w-full px-6 md:px-10 lg:px-12 py-3.5 transition-all duration-300">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="font-display font-bold text-[17px] sm:text-[19px] text-ink flex items-center gap-2.5 hover:opacity-90 transition-opacity">
                    <img src="{{ asset('logo.png') }}" alt="Logo PARTI" class="h-7 w-auto">
                    <span class="tracking-wide">PARTI {{ session('active_year', config('parti.active_year', 2026)) }}</span>
                </a>
                
                <div class="hidden md:flex gap-[28px] text-[14px] font-medium text-ink-soft">
                    <a href="{{ route('about') }}" class="hover:text-ember transition-colors py-1">Tentang</a>
                    <a href="{{ route('home') }}#sub-acara" class="hover:text-ember transition-colors py-1">Sub Acara</a>
                    <a href="{{ route('home') }}#timeline" class="hover:text-ember transition-colors py-1">Timeline</a>
                    <a href="{{ route('faq') }}" class="hover:text-ember transition-colors py-1 {{ request()->routeIs('faq') ? 'text-ember font-semibold' : '' }}">Pertanyaan</a>
                </div>

                <div class="hidden md:flex items-center gap-4">
                    <!-- Install PWA App Button (Desktop) -->
                    <button x-show="canInstallPwa"
                            x-cloak
                            @click="installPwa()"
                            class="font-mono text-[11px] font-bold tracking-wide uppercase bg-ember text-white px-[14px] py-[7px] rounded-full transition-all duration-300 hover:brightness-110 shadow-sm flex items-center gap-1.5 cursor-pointer animate-pulse"
                            title="Install PARTI 2026 App">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Install App
                    </button>

                    <!-- Theme Toggle Switch -->
                    <button @click="darkMode = !darkMode" class="p-2 rounded-full border border-line text-ink hover:bg-paper-warm hover:text-ember transition-all cursor-pointer" aria-label="Toggle Theme">
                        <!-- Sun icon (shown in dark mode) -->
                        <svg x-show="darkMode" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="5"></circle>
                            <line x1="12" y1="1" x2="12" y2="3"></line>
                            <line x1="12" y1="21" x2="12" y2="23"></line>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                            <line x1="1" y1="12" x2="3" y2="12"></line>
                            <line x1="21" y1="12" x2="23" y2="12"></line>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                        </svg>
                        <!-- Moon icon (shown in light mode) -->
                        <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                    </button>

                    <a class="font-mono text-[11px] tracking-wide uppercase bg-ink text-paper px-[18px] py-[8px] rounded-full transition-all duration-300 hover:opacity-90 shadow-sm" href="{{ route('home') }}#sub-acara">
                        Jelajahi Acara
                    </a>
                </div>

                <!-- Mobile Navbar Controls -->
                <div class="flex items-center gap-3.5 md:hidden">
                    <!-- Install PWA App Button (Mobile Icon Pill) -->
                    <button x-show="canInstallPwa"
                            x-cloak
                            @click="installPwa()"
                            class="p-2 rounded-full bg-ember text-white hover:brightness-110 transition-all cursor-pointer shadow-sm animate-pulse flex items-center justify-center"
                            aria-label="Install App">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                    </button>

                    <!-- Theme Toggle for Mobile -->
                    <button @click="darkMode = !darkMode" class="p-2 rounded-full border border-line text-ink hover:bg-paper-warm hover:text-ember transition-all cursor-pointer" aria-label="Toggle Theme">
                        <svg x-show="darkMode" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="5"></circle>
                            <line x1="12" y1="1" x2="12" y2="3"></line>
                            <line x1="12" y1="21" x2="12" y2="23"></line>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                            <line x1="1" y1="12" x2="3" y2="12"></line>
                            <line x1="21" y1="12" x2="23" y2="12"></line>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                        </svg>
                        <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                    </button>

                    <!-- Hamburger Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-ink focus:outline-none p-1.5 hover:text-ember transition-colors" aria-label="Toggle Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu Dropdown (iOS-like sheet) -->
            <div x-show="mobileMenuOpen" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-250"
                 x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
                 class="md:hidden bg-paper/95 dark:bg-paper-warm/95 rounded-xl border border-line mt-3 py-4 px-5 shadow-lg">
                <div class="flex flex-col gap-3.5 text-[14px] font-medium text-ink-soft">
                    <a href="{{ route('about') }}" @click="mobileMenuOpen = false" class="hover:text-ember py-2 border-b border-line/30 transition-colors">Tentang</a>
                    <a href="{{ route('home') }}#sub-acara" @click="mobileMenuOpen = false" class="hover:text-ember py-2 border-b border-line/30 transition-colors">Sub Acara</a>
                    <a href="{{ route('home') }}#timeline" @click="mobileMenuOpen = false" class="hover:text-ember py-2 border-b border-line/30 transition-colors">Timeline</a>
                    <a href="{{ route('faq') }}" @click="mobileMenuOpen = false" class="hover:text-ember py-2 border-b border-line/30 transition-colors {{ request()->routeIs('faq') ? 'text-ember font-semibold' : '' }}">Pertanyaan</a>
                    
                    <!-- Install App Button inside Mobile Menu -->
                    <button x-show="canInstallPwa"
                            x-cloak
                            @click="installPwa(); mobileMenuOpen = false;"
                            class="font-mono text-[11px] font-bold tracking-wide uppercase bg-ember text-white px-[20px] py-[10px] rounded-full text-center mt-2 hover:brightness-110 transition-all duration-300 shadow-sm flex items-center justify-center gap-2 cursor-pointer w-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Install App PARTI 2026
                    </button>

                    <a class="font-mono text-[11px] tracking-wide uppercase bg-ink text-paper px-[20px] py-[10px] rounded-full text-center mt-1 hover:opacity-90 transition-all duration-300 shadow-sm" href="{{ route('home') }}#sub-acara" @click="mobileMenuOpen = false">
                        Jelajahi Acara
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="flex-grow transition-all duration-[1000ms] ease-premium transform delay-[200ms]"
          :class="isPageLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">
        @yield('content')
    </main>

    <!-- Full-Width Edge-to-Edge Footer -->
    <footer class="w-full bg-paper-warm border-t border-line text-ink pt-16 pb-[34px] transition-opacity duration-[1000ms] ease-premium delay-[300ms]"
            :class="isPageLoaded ? 'opacity-100' : 'opacity-0'">
        <div class="w-full px-6 md:px-10 lg:px-12">
            <div class="flex flex-col md:flex-row justify-between items-start pb-11 border-b border-line/60 gap-8">
                <div>
                    <div class="font-display text-[20px] font-bold flex items-center gap-2.5">
                        <img src="{{ asset('logo.png') }}" alt="Logo PARTI" class="h-7 w-auto">
                        <span class="tracking-wide">PARTI <span class="text-ember">{{ session('active_year', config('parti.active_year', 2026)) }}</span></span>
                    </div>
                    <p class="text-[13px] text-ink-soft mt-2.5 max-w-[32ch]">Vanguard of Tech | HIMATIF Universitas Muhammadiyah Surakarta.</p>
                    
                    <!-- Penyelenggara & Naungan Logos -->
                    <div class="mt-5 pt-4 border-t border-line/40">
                        <span class="block font-mono text-[9px] tracking-widest uppercase text-ink-soft/70 font-bold mb-2.5">DISELENGGARAKAN OLEH</span>
                        <div class="flex items-center gap-4 flex-wrap">
                            @foreach($organizers as $org)
                            <a href="{{ $org['url'] }}" target="_blank" rel="noopener noreferrer" class="group flex items-center gap-1.5 transition-all duration-300" title="{{ $org['full_name'] }}">
                                <img src="{{ $org['logo'] }}" alt="{{ $org['nama'] }}" x-show="darkMode" class="h-6 w-auto object-contain filter grayscale brightness-125 opacity-70 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-300">
                                <img src="{{ $org['logo_light'] }}" alt="{{ $org['nama'] }}" x-show="!darkMode" x-cloak class="h-6 w-auto object-contain filter opacity-85 group-hover:opacity-100 transition-all duration-300">
                                <span class="font-mono text-[9px] tracking-wider uppercase text-ink-soft/75 group-hover:text-ember transition-colors font-bold">{{ $org['nama'] }}</span>
                            </a>
                            @if(!$loop->last)
                            <div class="h-3.5 w-[1px] bg-line/60"></div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-12 sm:gap-16">
                    <div class="text-left">
                        <h5 class="font-mono text-[10px] tracking-[0.15em] uppercase text-ember font-bold mb-4">Acara</h5>
                        @forelse($footerSubEvents as $sub)
                            <a href="{{ route('sub-event.show', $sub->slug) }}" class="block text-[13px] text-ink-soft mb-2.5 hover:text-ember transition-colors">{{ $sub->name }}</a>
                        @empty
                            <a href="{{ route('home') }}#sub-acara" class="block text-[13px] text-ink-soft mb-2.5 hover:text-ember transition-colors">Lihat Semua Acara</a>
                        @endforelse
                    </div>
                    <div class="text-left">
                        <h5 class="font-mono text-[10px] tracking-[0.15em] uppercase text-ember font-bold mb-4">Jelajah</h5>
                        <a href="{{ route('about') }}" class="block text-[13px] text-ink-soft mb-2.5 hover:text-ember transition-colors">Tentang</a>
                        <a href="{{ route('faq') }}" class="block text-[13px] text-ink-soft mb-2.5 hover:text-ember transition-colors">Pertanyaan</a>
                        <a href="{{ route('home') }}#timeline" class="block text-[13px] text-ink-soft mb-2.5 hover:text-ember transition-colors">Timeline</a>
                    </div>
                    <div class="text-left">
                        <h5 class="font-mono text-[10px] tracking-[0.15em] uppercase text-ember font-bold mb-4">Media Sosial</h5>
                        @if(config('parti.socials.parti.instagram'))
                            <a href="{{ config('parti.socials.parti.instagram') }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 text-[13px] text-ink-soft mb-2.5 hover:text-ember transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                    <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                                    <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"></path>
                                    <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                                </svg>
                                Instagram
                            </a>
                        @endif
                        @if(config('parti.socials.parti.tiktok'))
                            <a href="{{ config('parti.socials.parti.tiktok') }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 text-[13px] text-ink-soft mb-2.5 hover:text-ember transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path>
                                </svg>
                                TikTok
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="pt-7 flex flex-col sm:flex-row justify-between text-[11.5px] text-ink-soft/75 gap-2.5">
                <span>© {{ session('active_year', config('parti.active_year', 2026)) }} HIMATIF UMS. Seluruh hak cipta dilindungi.</span>
                {{-- ponytail: removed redundant Vanguard macOS Edition tag --}}
            </div>
        </div>
    </footer>
    <!-- PWA Service Worker Registration -->
    <script>
        // Developer signature & Version Codename
        console.log(
            '%c PARTI 2026 %c v1.0.0 "Vanguard" %c Crafted with precision by AtnanLabs %c https://www.atnan.my.id/ ',
            'background: #1C140B; color: #FAF8F5; font-weight: 800; border-radius: 4px 0 0 4px; padding: 3px 8px; font-family: monospace;',
            'background: #EA580C; color: #FFFFFF; font-weight: 700; padding: 3px 8px; font-family: monospace;',
            'background: #27272A; color: #E4E4E7; font-weight: 600; padding: 3px 8px; font-family: monospace;',
            'background: #09090B; color: #38BDF8; font-family: monospace; border-radius: 0 4px 4px 0; padding: 3px 8px; text-decoration: underline;'
        );

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('{{ asset("sw.js") }}')
                    .then(function(reg) {
                        console.log('PARTI PWA ServiceWorker registered with scope:', reg.scope);
                    })
                    .catch(function(err) {
                        console.log('PARTI PWA ServiceWorker registration failed:', err);
                    });
            });
        }
    </script>
</body>
</html>
