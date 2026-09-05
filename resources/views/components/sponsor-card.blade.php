{{--
  Komponen: Kartu Sponsor Individual
  Tujuan: Menampilkan logo atau badge nama sponsor secara terisolasi.
  Mendukung tiga varian ukuran (large, medium, small), status unggulan (isFeatured),
  serta interaksi hover/tap dengan tooltip nama sponsor dan opsi navigasi tautan luar.
--}}

@props([
    'sponsor',
    'size' => 'medium',
    'loading' => 'lazy',
    'isFeatured' => false
])

@php
    $sizeClasses = [
        'large' => 'px-4 sm:px-6 py-2.5 sm:py-3.5 min-h-[48px] sm:min-h-[58px] min-w-[130px] sm:min-w-[160px] rounded-xl sm:rounded-2xl border border-line/70 dark:border-white/10 bg-white dark:bg-white/[0.06] text-ink dark:text-stone-100 shadow-xs hover:border-ember/60 dark:hover:border-ember/60 hover:shadow-md transition-all duration-300',
        'medium' => 'px-3.5 sm:px-5 py-2 sm:py-3 min-h-[42px] sm:min-h-[50px] min-w-[110px] sm:min-w-[130px] rounded-lg sm:rounded-xl border border-line/70 dark:border-white/10 bg-white dark:bg-white/[0.05] text-ink dark:text-stone-100 shadow-xs hover:border-ember/60 dark:hover:border-ember/60 hover:shadow-md transition-all duration-300',
        'small' => 'px-3 sm:px-4 py-1.5 sm:py-2.5 min-h-[36px] sm:min-h-[44px] min-w-[95px] sm:min-w-[110px] rounded-md sm:rounded-lg border border-line/70 dark:border-white/10 bg-white dark:bg-white/[0.04] text-ink dark:text-stone-100 shadow-xs hover:border-ember/60 dark:hover:border-ember/60 hover:shadow-md transition-all duration-300',
    ];

    $imgClasses = [
        'large' => 'h-[28px] sm:h-[40px]',
        'medium' => 'h-[22px] sm:h-[32px]',
        'small' => 'h-[18px] sm:h-[25px]',
    ];

    $hasUrl = !empty($sponsor->website_url);
    
    // Periksa apakah file gambar logo fisik tersedia atau berupa URL eksternal
    $hasValidLogo = false;
    if (!empty($sponsor->logo_path)) {
        if (str_starts_with($sponsor->logo_path, 'http://') || str_starts_with($sponsor->logo_path, 'https://')) {
            $hasValidLogo = true;
        } else {
            $fullPath = storage_path('app/public/' . $sponsor->logo_path);
            $publicPath = public_path('storage/' . $sponsor->logo_path);
            if ((file_exists($fullPath) && filesize($fullPath) > 20) || (file_exists($publicPath) && filesize($publicPath) > 20)) {
                $hasValidLogo = true;
            }
        }
    }
@endphp

<div class="relative group/card shrink-0 inline-flex items-center"
     x-data="{ isHovered: false }"
     @mouseenter="isHovered = true"
     @mouseleave="isHovered = false"
     @focusin="isHovered = true"
     @focusout="isHovered = false"
     @touchstart="isHovered = true"
     @click.away="isHovered = false">

    {{-- ponytail: removed absolute tooltip div that was being clipped by marquee container overflow-hidden --}}

    <!-- Kartu Sponsor (Navigasi Link Website / Kotak Tampilan) -->
    <{{ $hasUrl ? 'a' : 'div' }}
        title="{{ $sponsor->name }}"
        @if($hasUrl)
            href="{{ $sponsor->website_url }}"
            target="_blank"
            rel="noopener sponsored"
        @endif
        tabindex="0"
        :class="{
            '-translate-y-0.5 sm:-translate-y-1 scale-105 shadow-md border-ember/60 dark:border-ember/60 bg-white dark:bg-white/[0.12]': isHovered,
            '{{ $sizeClasses[$size] }}': !isHovered
        }"
        class="{{ $sizeClasses[$size] }} flex items-center justify-center relative transition-all duration-300 cursor-pointer focus:outline-none focus:ring-1 focus:ring-ember/40">
        
        @if($hasValidLogo)
            <img src="{{ $sponsor->logo_url }}" 
                 alt="{{ $sponsor->name }}" 
                 loading="{{ $loading }}"
                 :class="{
                    'scale-105 opacity-100': isHovered,
                    'opacity-90 dark:opacity-95': !isHovered
                 }"
                 class="{{ $imgClasses[$size] }} w-auto max-w-[100px] sm:max-w-[140px] object-contain transition-all duration-300 filter-gpu">
        @else
            <div class="flex items-center gap-1.5 sm:gap-2 px-0.5 sm:px-1">
                <span class="w-1.5 h-1.5 rounded-full bg-gold/80 transition-colors" :class="{ 'bg-ember': isHovered }"></span>
                <span :class="{ 'text-ember': isHovered, 'text-ink dark:text-stone-100 font-bold': !isHovered }"
                      class="font-mono text-[10.5px] sm:text-[12.5px] uppercase tracking-wider transition-colors text-center whitespace-nowrap">
                    {{ $sponsor->name }}
                </span>
            </div>
        @endif
    </{{ $hasUrl ? 'a' : 'div' }}>
</div>
