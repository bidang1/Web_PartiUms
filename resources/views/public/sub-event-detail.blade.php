@extends('layouts.public')

@section('title', $subEvent->name . ' | PARTI ' . session('active_year', config('parti.active_year', 2026)))
@section('meta_description', $subEvent->tagline ?? Str::limit(strip_tags($subEvent->description ?? $subEvent->name), 155))
@section('meta_keywords', $subEvent->name . ', ' . ($subEvent->tagline ? $subEvent->tagline . ', ' : '') . 'PARTI 2026, HIMATIF UMS, lomba IT, kompetisi ' . $subEvent->type)
@section('og_title', $subEvent->name . ' | PARTI ' . session('active_year', config('parti.active_year', 2026)))
@section('og_description', $subEvent->tagline ?? Str::limit(strip_tags($subEvent->description ?? ''), 150))
@section('og_image', $subEvent->poster_url ?? asset('logo.png'))

@section('structured_data')
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'Event',
  'name' => $subEvent->name,
  'description' => $subEvent->tagline ?? Str::limit(strip_tags($subEvent->description ?? ''), 200),
  'image' => $subEvent->poster_url ?? asset('logo.png'),
  'url' => request()->url(),
  'startDate' => $subEvent->date_start ? $subEvent->date_start->toIso8601String() : null,
  'endDate' => $subEvent->date_end ? $subEvent->date_end->toIso8601String() : ($subEvent->date_start ? $subEvent->date_start->toIso8601String() : null),
  'eventAttendanceMode' => 'https://schema.org/' . ($subEvent->type === 'ONLINE' ? 'OnlineEventAttendanceMode' : ($subEvent->type === 'OFFLINE' ? 'OfflineEventAttendanceMode' : 'MixedEventAttendanceMode')),
  'eventStatus' => 'https://schema.org/EventScheduled',
  'location' => $subEvent->type === 'ONLINE' ? [
    '@type' => 'VirtualLocation',
    'url' => (is_array($subEvent->gform_link) && count($subEvent->gform_link) > 0) ? ($subEvent->gform_link[0]['url'] ?? request()->url()) : request()->url()
  ] : [
    '@type' => 'Place',
    'name' => $subEvent->location ?? 'Universitas Muhammadiyah Surakarta',
    'address' => [
      '@type' => 'PostalAddress',
      'addressLocality' => 'Surakarta',
      'addressRegion' => 'Jawa Tengah',
      'addressCountry' => 'ID'
    ]
  ],
  'organizer' => [
    '@type' => 'Organization',
    'name' => 'HIMATIF UMS',
    'url' => 'https://himatifums.org/'
  ]
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('breadcrumb_data')
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'BreadcrumbList',
  'itemListElement' => [
    [
      '@type' => 'ListItem',
      'position' => 1,
      'name' => 'Beranda',
      'item' => route('home')
    ],
    [
      '@type' => 'ListItem',
      'position' => 2,
      'name' => 'Sub Acara',
      'item' => route('home') . '#sub-acara'
    ],
    [
      '@type' => 'ListItem',
      'position' => 3,
      'name' => $subEvent->name,
      'item' => request()->url()
    ]
  ]
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
<!-- DETAIL PAGE HEADER (macOS Floating Pane) -->
<section class="relative py-8 px-4 max-w-[1140px] mx-auto z-10">
    <div class="ios-glass rounded-[32px] p-8 md:p-12 relative overflow-hidden">
        <div class="flex items-center gap-3.5 mb-6">
            <a href="{{ route('home') }}" class="font-mono text-[11px] text-ink-soft hover:text-ember transition-colors flex items-center gap-2 font-bold uppercase tracking-wider">
                ← Kembali ke Beranda
            </a>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <span class="font-mono text-[11px] tracking-[0.2em] uppercase text-ink flex items-center gap-2.5 before:content-[''] before:w-[20px] before:h-[1px] before:bg-ember font-bold">
                    Sub Acara
                </span>
                <h1 class="font-display font-bold text-[28px] sm:text-[34px] md:text-[46px] mt-4 mb-2 text-ink uppercase tracking-tight leading-tight">
                    {{ $subEvent->name }}
                </h1>
                @if($subEvent->tagline)
                <p class="font-display text-[15px] sm:text-[16px] italic text-ink-soft border-l-2 border-gold pl-4 mt-2">
                    {{ $subEvent->tagline }}
                </p>
                @endif
                <div class="text-ink-soft leading-relaxed text-[14px] sm:text-[14.5px] mt-4 pt-4 border-t border-line/50 max-w-[75ch]">
                    {!! nl2br(e($subEvent->description)) !!}
                </div>
            </div>

            @php
                $statusColors = match($subEvent->registration_button_state) {
                    'open' => [
                        'badge' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                        'dot' => 'bg-emerald-500 glowing-beacon',
                        'label' => 'Pendaftaran Buka',
                    ],
                    'closed' => [
                        'badge' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                        'dot' => 'bg-rose-500',
                        'label' => 'Pendaftaran Tutup',
                    ],
                    default => [
                        'badge' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                        'dot' => 'bg-amber-500 glowing-beacon',
                        'label' => 'Segera Dibuka',
                    ],
                };
            @endphp
            <!-- LED Registration status badge -->
            <div class="flex items-center gap-4 bg-paper/80 dark:bg-paper-warm/80 border border-line px-5 py-3 rounded-full shadow-sm">
                <span class="font-mono text-[9px] tracking-[0.05em] uppercase px-3.5 py-1 rounded-full font-bold flex items-center gap-1.5 whitespace-nowrap border {{ $statusColors['badge'] }}">
                    <span class="w-1.5 h-1.5 rounded-full inline-block {{ $statusColors['dot'] }}"></span>
                    {{ $statusColors['label'] }}
                </span>
            </div>
        </div>
    </div>
</section>

<!-- DETAIL CONTENT (iOS Modular Grid) -->
<section class="py-6 px-4 max-w-[1140px] mx-auto z-10 relative mb-12">
    <div class="grid grid-cols-1 md:grid-cols-[1.2fr_0.8fr] gap-8 md:gap-12">
        
        <!-- Right Column: Registration Card, Info details, Share tools (Appears first on mobile) -->
        <div class="space-y-8 text-left order-1 md:order-2">
            <!-- Registration Action Card -->
            <div class="ios-glass rounded-[24px] p-6 sm:p-8 relative shadow-sm">
                <!-- Apple traffic light buttons decoration -->
                <div class="flex items-center gap-1.5 mb-5 border-b border-line pb-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#FF5F56] inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-[#FFBD2E] inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-[#27C93F] inline-block"></span>
                </div>
                
                <h4 class="font-display font-bold text-[16px] text-ink uppercase tracking-wider mb-5">Pendaftaran Peserta</h4>
                
                @if($subEvent->registration_button_state === 'open' && is_array($subEvent->gform_link))
                    <div class="space-y-3">
                        @foreach($subEvent->gform_link as $link)
                            <a href="{{ $link['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="w-full text-center bg-gradient-to-r from-ember to-ember-dark text-white font-semibold text-[13px] px-[24px] py-[13px] rounded-full inline-flex items-center justify-center gap-2 transition-all duration-300 hover:shadow-[0_8px_20px_-4px_rgba(255,107,0,0.4)] hover:-translate-y-0.5 active:translate-y-0 uppercase tracking-wider font-mono shadow-sm">
                                {{ $link['label'] ?? 'Daftar' }}
                            </a>
                        @endforeach
                    </div>
                @elseif($subEvent->registration_button_state === 'closed')
                    <button disabled class="w-full text-center bg-black/10 dark:bg-white/10 text-ink-soft/40 font-semibold text-[13px] px-[24px] py-[13px] rounded-full inline-flex items-center justify-center cursor-not-allowed border border-line uppercase tracking-wider font-mono">
                        Pendaftaran Ditutup
                    </button>
                @else
                    <button disabled class="w-full text-center bg-black/10 dark:bg-white/10 text-ink-soft/40 font-semibold text-[13px] px-[24px] py-[13px] rounded-full inline-flex items-center justify-center cursor-not-allowed border border-line uppercase tracking-wider font-mono">
                        Segera Dibuka
                    </button>
                @endif
            </div>

            <!-- Event Details Checklist -->
            <div class="ios-glass rounded-[24px] p-6 sm:p-8 space-y-6 shadow-sm">
                <div>
                    <h5 class="font-mono text-[9px] tracking-[0.1em] uppercase text-ink-soft font-bold mb-1.5">Pelaksanaan</h5>
                    <p class="font-semibold text-[14px] sm:text-[14.5px] text-ink">
                        @if($subEvent->date_start)
                            @if($subEvent->date_end && $subEvent->date_start != $subEvent->date_end)
                                {{ $subEvent->date_start->translatedFormat('d M') }} s/d {{ $subEvent->date_end->translatedFormat('d M Y') }}
                            @else
                                {{ $subEvent->date_start->translatedFormat('d M Y') }}
                            @endif
                        @else
                            TBD (Akan Diumumkan)
                        @endif
                    </p>
                </div>

                @if($subEvent->htm_tiers)
                    <div>
                        <h5 class="font-mono text-[9px] tracking-[0.1em] uppercase text-ink-soft font-bold mb-2">Harga Tiket Masuk (HTM)</h5>
                        <div class="space-y-2">
                            @foreach($subEvent->htm_tiers as $tier)
                                <div class="flex justify-between items-center text-[13.5px] border-b border-line pb-1.5 last:border-0 last:pb-0">
                                    <span class="text-ink-soft font-mono">{{ $tier['label'] }}</span>
                                    <span class="font-semibold text-ink">
                                        @if(empty($tier['price']) || $tier['price'] == 0)
                                            Gratis
                                        @else
                                            Rp {{ number_format($tier['price'], 0, ',', '.') }}
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="space-y-4 pt-4 border-t border-line">
                    <div>
                        <h5 class="font-mono text-[9px] tracking-[0.1em] uppercase text-ink-soft font-bold mb-1">Format Pelaksanaan</h5>
                        <p class="font-semibold text-[14px] text-ink">
                            {{ $subEvent->type }}
                        </p>
                    </div>
                    @if($subEvent->location)
                        <div>
                            <h5 class="font-mono text-[9px] tracking-[0.1em] uppercase text-ink-soft font-bold mb-1">Lokasi / Ruang</h5>
                            <p class="font-semibold text-[14px] text-ink flex items-center gap-1">
                                📍 {{ $subEvent->location }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Share Event Card (Zero Purple) -->
            <div class="ios-glass rounded-[24px] p-6 space-y-4 shadow-sm">
                <h5 class="font-mono text-[10px] tracking-[0.12em] uppercase text-ink font-bold">Bagikan Acara</h5>
                <p class="text-[13px] text-ink-soft leading-relaxed">
                    Ajak rekan satu tim-mu untuk mendaftar dengan membagikan tautan acara ini.
                </p>
                <div class="flex flex-wrap gap-2.5">
                    <!-- WhatsApp -->
                    <a href="https://api.whatsapp.com/send?text={{ rawurlencode($subEvent->name . ' | PARTI ' . session('active_year', config('parti.active_year', 2026)) . ': ' . request()->url()) }}" 
                       target="_blank" rel="noopener noreferrer" 
                       class="flex items-center justify-center w-9 h-9 rounded-full border border-line hover:border-emerald-500 hover:text-emerald-500 dark:hover:bg-emerald-500/10 text-ink-soft transition-all"
                       title="Bagikan ke WhatsApp">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984a9.96 9.96 0 001.335 4.992L2 22l5.163-1.355a9.95 9.95 0 004.847 1.256h.004c5.507 0 9.99-4.478 9.99-9.986 0-2.67-1.037-5.178-2.924-7.065A9.92 9.92 0 0012.012 2zm5.836 14.16c-.32.9-1.859 1.76-2.548 1.83-.58.06-1.34.1-3.83-.93-3.19-1.32-5.25-4.57-5.41-4.79-.16-.22-1.28-1.71-1.28-3.26 0-1.55.81-2.31 1.1-2.61.29-.3.63-.38.84-.38.21 0 .42 0 .61.01.2.01.47-.08.73.56.27.65.91 2.24.99 2.4.08.16.13.35.03.55-.1.2-.15.3-.3.48-.15.18-.32.4-.46.54-.15.15-.31.31-.13.62.18.3.79 1.3 1.69 2.1 1.16 1.03 2.13 1.35 2.43 1.5.3.15.48.13.66-.08.18-.22.79-.92 1.01-1.23.21-.32.43-.27.73-.16.3.11 1.91.9 2.24 1.06.33.16.55.24.63.38.08.14.08.82-.24 1.72z"/>
                        </svg>
                    </a>
                    <!-- Twitter / X -->
                    <a href="https://twitter.com/intent/tweet?text={{ rawurlencode($subEvent->name . ' | PARTI ' . session('active_year', config('parti.active_year', 2026))) }}&url={{ rawurlencode(request()->url()) }}" 
                       target="_blank" rel="noopener noreferrer" 
                       class="flex items-center justify-center w-9 h-9 rounded-full border border-line hover:border-sky-500 hover:text-sky-500 dark:hover:bg-sky-500/10 text-ink-soft transition-all"
                       title="Bagikan ke X">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    <!-- Telegram -->
                    <a href="https://t.me/share/url?url={{ rawurlencode(request()->url()) }}&text={{ rawurlencode($subEvent->name . ' | PARTI ' . session('active_year', config('parti.active_year', 2026))) }}" 
                       target="_blank" rel="noopener noreferrer" 
                       class="flex items-center justify-center w-9 h-9 rounded-full border border-line hover:border-blue-500 hover:text-blue-500 dark:hover:bg-blue-500/10 text-ink-soft transition-all"
                       title="Bagikan ke Telegram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.07-.2-.08-.06-.19-.04-.27-.02-.12.02-1.96 1.24-5.54 3.65-.52.36-.99.53-1.41.52-.46-.01-1.35-.26-2.01-.48-.81-.27-1.46-.42-1.4-.89.03-.25.38-.51 1.07-.78 4.2-1.83 7-3.04 8.4-3.63 4-.16 4.83.69 4.84.81z"/>
                        </svg>
                    </a>
                    <!-- Copy Link -->
                    <div x-data="{ copied: false }">
                        <button @click="navigator.clipboard.writeText('{{ request()->url() }}').then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                                class="flex items-center justify-center w-9 h-9 rounded-full border border-line text-ink-soft hover:bg-paper-warm transition-all"
                                :class="copied ? 'border-emerald-500 text-emerald-500 bg-emerald-500/10' : 'hover:border-ember hover:text-ember dark:hover:bg-white/[0.08]'"
                                :title="copied ? 'Tautan disalin!' : 'Salin Tautan'">
                            <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                <rect width="14" height="14" x="8" y="8" rx="2" ry="2"></rect>
                                <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"></path>
                            </svg>
                            <svg x-show="copied" x-cloak class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Left Column: Details (Appears second on mobile) -->
        <div class="order-2 md:order-1 space-y-8">
            @if($subEvent->poster_path)
                <div class="ios-glass overflow-hidden rounded-[24px] p-4 transition-transform duration-500 hover:scale-[1.01]">
                    <img src="{{ $subEvent->poster_url }}" alt="Poster {{ $subEvent->name }}" class="w-full h-auto object-contain max-h-[600px] rounded-[16px] mx-auto" />
                </div>
            @endif

            {{-- ponytail: merged event description into top hero card and deleted separate sheet --}}

            <!-- Downloadable Documents Section as iOS Card -->
            @if($subEvent->documents->isNotEmpty())
                <div class="ios-glass rounded-[24px] p-6 sm:p-8 text-left relative shadow-sm">
                    <h4 class="font-display font-semibold text-[17px] sm:text-[18px] text-ink mb-4 flex items-center gap-2 uppercase tracking-wide">
                        📄 Unduh Berkas Panduan
                    </h4>
                    <p class="text-[13.5px] text-ink-soft mb-6 leading-relaxed">
                        Harap unduh dan lengkapi berkas panduan di bawah ini sebelum melanjutkan ke pendaftaran Google Form.
                    </p>
                    <div class="space-y-3.5">
                        @foreach($subEvent->documents as $doc)
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between bg-black/[0.03] dark:bg-white/[0.03] border border-line p-4 rounded-[12px] gap-4">
                                <div class="flex items-start sm:items-center gap-3">
                                    <span class="font-mono text-[9px] bg-orange-500/10 text-orange-600 dark:text-orange-400 border border-orange-500/20 px-2.5 py-0.5 rounded-full font-bold uppercase whitespace-nowrap">
                                        {{ $doc->file_type }}
                                    </span>
                                    <div class="flex flex-wrap items-center gap-x-2">
                                        <span class="font-semibold text-[14px] sm:text-[14.5px] text-ink">{{ $doc->label }}</span>
                                        <span class="text-[11px] text-ink-soft">({{ $doc->file_size_formatted }})</span>
                                    </div>
                                </div>
                                <a href="{{ route('document.download', $doc->id) }}" class="font-mono text-[11px] text-ember hover:text-ember-dark font-bold border-b border-ember hover:border-ember-dark pb-0.5 self-start sm:self-auto whitespace-nowrap">
                                    Unduh Berkas ↓
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        
    </div>
</section>

@endsection
