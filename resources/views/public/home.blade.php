@extends('layouts.public')

@section('title', 'PARTI ' . session('active_year', config('parti.active_year', 2026)) . ' | Parade Teknik Informatika HIMATIF UMS')
@section('meta_description', 'Website resmi PARTI (Parade Teknik Informatika) HIMATIF Universitas Muhammadiyah Surakarta. Platform informasi dan pendaftaran kompetisi, workshop, serta festival IT.')

@section('structured_data')
@php
    $startDates = $subEvents->whereNotNull('date_start')->pluck('date_start')->sort();
    $endDates = $subEvents->whereNotNull('date_end')->pluck('date_end')->sort();
    $earliestDate = $startDates->first();
    $latestDate = $endDates->last() ?? $startDates->last();
@endphp
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'EventSeries',
  'name' => 'PARTI ' . session('active_year', config('parti.active_year', 2026)),
  'description' => 'Parade Teknik Informatika HIMATIF Universitas Muhammadiyah Surakarta',
  'url' => url('/'),
  'startDate' => $earliestDate ? $earliestDate->toIso8601String() : null,
  'endDate' => $latestDate ? $latestDate->toIso8601String() : null,
  'organizer' => [
    '@type' => 'EducationalOrganization',
    'name' => 'HIMATIF UMS',
    'url' => 'https://himatifums.org/'
  ],
  'eventStatus' => 'https://schema.org/EventScheduled',
  'location' => [
    '@type' => 'Place',
    'name' => 'Universitas Muhammadiyah Surakarta',
    'address' => [
      '@type' => 'PostalAddress',
      'addressLocality' => 'Surakarta',
      'addressRegion' => 'Jawa Tengah',
      'addressCountry' => 'ID'
    ]
  ],
  'subEvent' => $subEvents->map(function($sub) {
    return [
      '@type' => 'Event',
      'name' => $sub->name,
      'description' => $sub->tagline ?? Str::limit(strip_tags($sub->description ?? ''), 150),
      'url' => route('sub-event.show', $sub->slug),
      'startDate' => $sub->date_start ? $sub->date_start->toIso8601String() : null,
      'endDate' => $sub->date_end ? $sub->date_end->toIso8601String() : ($sub->date_start ? $sub->date_start->toIso8601String() : null),
    ];
  })->values()->all()
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
<!-- HERO SECTION -->
<section class="relative min-h-[80vh] flex items-center py-12 md:py-24 overflow-hidden">
    <!-- WebGL Background Removed in favor of Interactive Retro Terminal -->

    <!-- Main Content -->
    <div class="w-full max-w-[1140px] mx-auto px-6 md:px-8 relative z-10 pointer-events-none my-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center text-center md:text-left">
            <!-- macOS Terminal Window Container -->
            <div class="pointer-events-auto text-left mx-auto md:mx-0 w-full rounded-[28px] ios-glass overflow-hidden">
                <!-- Window Title Bar -->
                <div class="relative flex items-center justify-between px-5 py-3.5 border-b border-line bg-black/[0.03] dark:bg-white/[0.03]">
                    <div class="flex items-center gap-1.5 z-10">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#FF5F56] inline-block shadow-sm"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-[#FFBD2E] inline-block shadow-sm"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-[#27C93F] inline-block shadow-sm"></span>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <span class="font-mono text-[9px] tracking-wider uppercase text-ink-soft/60 select-none font-semibold">parti - terminal</span>
                    </div>
                    <div class="w-10"></div>
                </div>
                <!-- Window Content -->
                <div class="p-6 sm:p-8 md:p-10">
                    <span class="font-mono text-[11px] tracking-[0.2em] uppercase text-ink flex items-center gap-2.5 before:content-[''] before:w-[20px] before:h-[1px] before:bg-ember animate-fade-in font-bold">
                        PARTI HIMATIF UMS
                    </span>

                    <h1 class="font-display font-bold leading-[1.05] text-[32px] sm:text-[38px] md:text-[46px] mt-4 mb-5 text-ink uppercase tracking-tight">
                        PARADE TEKNIK<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-ember to-gold">INFORMATIKA</span>
                    </h1>

                    <p class="font-display text-[15px] sm:text-[17px] italic text-ink-soft mb-5 border-l-2 border-gold pl-4 max-w-[46ch]">
                        Wadah Inovasi, Kreativitas, dan Kolaborasi Teknologi
                    </p>

                    <p class="text-[14px] sm:text-[14.5px] leading-relaxed text-ink-soft max-w-[50ch] mb-8">
                        PARTI (Parade Teknik Informatika) adalah rangkaian event tahunan terbesar yang diselenggarakan oleh HIMATIF UMS. Berbagai sub-acara kompetisi, seminar, dan workshop dirancang untuk mengasah potensi, keilmuan, serta semangat berinovasi mahasiswa dan publik.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <a href="#sub-acara" class="w-full sm:w-auto bg-gradient-to-r from-ember to-ember-dark text-white font-semibold text-[13px] px-[28px] py-[13px] rounded-full inline-flex items-center justify-center gap-2 transition-all duration-300 hover:shadow-[0_8px_20px_-4px_rgba(255,107,0,0.4)] hover:-translate-y-0.5 active:translate-y-0 text-center uppercase tracking-wider font-mono shadow-sm">
                            Jelajahi Acara ↓
                        </a>
                        <a href="{{ route('about') }}" class="w-full sm:w-auto font-mono text-[11px] text-ink-soft hover:text-ember transition-colors text-center py-2 relative group font-bold">
                            [ Tentang PARTI ]
                        </a>
                    </div>
                </div>
            </div>

            <!-- Retro Terminal (Desktop Only) -->
            <div class="hidden md:flex flex-col pointer-events-auto text-left w-full h-[400px] rounded-[20px] bg-[#0c0c0c] border border-gray-800 shadow-[0_0_40px_rgba(39,201,63,0.1)] overflow-hidden font-mono text-[13px] relative"
                style="display: flex; flex-direction: column;"
                x-data="{
                    input: '',
                    output: [
                        'Welcome to PARTI 2026 Interactive Shell v1.0',
                        'Type \'help\' to see available commands.',
                        ''
                    ],
                    commands: {
                        'help': 'Available commands: help, about, version, date, clear, sudo',
                        'version': 'PARTI 2026 Core Platform v1.0.0 (Codename: \'Vanguard\') - Hostinger Production Build\nEngineered & Crafted with precision by AtnanLabs (https://www.atnan.my.id/)',
                        'about': 'PARTI (Parade Teknik Informatika) is the biggest annual event by HIMATIF UMS. It is a hub for innovation, creativity, and technology collaboration.',
                        'date': new Date().toLocaleString(),
                        'sudo': 'Nice try, human. Access denied.',
                        'whoami': 'Engineered & Crafted with passion by AtnanLabs (https://www.atnan.my.id/)',
                        'atnanlabs': 'AtnanLabs - Vanguard of Web Engineering & Digital Experiences (https://www.atnan.my.id/)',
                        'credits': 'Platform Engineering: AtnanLabs (https://www.atnan.my.id/) | Organizer: HIMATIF UMS'
                    },
                    execute() {
                        const cmd = this.input.trim().toLowerCase();
                        this.output.push('> ' + this.input);
                        this.input = '';
                        
                        if (cmd === '') return;
                        if (cmd === 'clear') {
                            this.output = [];
                            return;
                        }
                        if (this.commands[cmd]) {
                            this.output.push(this.commands[cmd]);
                        } else {
                            this.output.push('Command not found: ' + cmd);
                        }
                        
                        // Auto scroll to bottom
                        this.$nextTick(() => {
                            const term = this.$refs.terminalBody;
                            term.scrollTop = term.scrollHeight;
                        });
                    }
                }"
                @click="$refs.cmdInput.focus()"
            >
                <!-- Top Bar -->
                <div class="w-full bg-[#1a1a1a] px-4 py-3 border-b border-gray-800 flex items-center justify-between shrink-0" style="width: 100%; display: flex; flex-direction: row;">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full bg-[#FF5F56]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#FFBD2E]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#27C93F]"></div>
                    </div>
                    <div class="text-gray-400 text-[10px] tracking-widest font-bold font-mono">GUEST@PARTI2026:~</div>
                    <div class="w-10"></div>
                </div>
                
                <!-- Terminal Body -->
                <div x-ref="terminalBody" class="p-5 flex-1 overflow-y-auto text-[#27C93F] font-mono flex flex-col gap-1 scrollbar-hide shadow-inner" style="min-height: 0; flex: 1; overflow-y: auto;">
                    <template x-for="(line, index) in output" :key="index">
                        <div class="whitespace-pre-wrap break-words" x-text="line"></div>
                    </template>
                    <div class="flex items-center mt-1">
                        <span class="text-[#27C93F] mr-2">></span>
                        <input 
                            x-ref="cmdInput"
                            type="text" 
                            x-model="input" 
                            @keydown.enter="execute"
                            class="bg-transparent border-none outline-none text-[#27C93F] flex-1 font-mono focus:ring-0 p-0 shadow-none"
                            autofocus
                            autocomplete="off"
                            spellcheck="false"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION SPONSOR LOGOLOOP (Ditempatkan Tepat Di Bawah Section Hero) -->
<x-sponsor-section :sponsors="$sponsors" />

{{-- ponytail: removed inline tentang section in favor of dedicated /tentang route --}}

<!-- SUB ACARA SECTION (Modular Grid Section) -->
<section class="py-12 px-4 max-w-[1140px] mx-auto z-10 relative" id="sub-acara">
    <div class="mb-8 text-left">
        <h2 class="font-display font-bold text-[26px] sm:text-[32px] text-ink uppercase tracking-tight">
            Sub Acara PARTI {{ session('active_year', config('parti.active_year', 2026)) }}
        </h2>
    </div>

    <!-- Minimalist Cards Grid -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 md:gap-7">
        @forelse($subEvents as $subEvent)
        <a href="{{ route('sub-event.show', $subEvent->slug) }}"
            class="w-full group flex flex-col ios-glass rounded-[24px] p-6 sm:p-7 relative text-left transition-all duration-300 hover:-translate-y-1">

            <!-- Top Bar: Date & Status -->
            <div class="flex items-center justify-between mb-4">
                <span class="font-mono text-[11px] text-ink-soft">
                    @if($subEvent->date_start && $subEvent->date_end && $subEvent->date_start != $subEvent->date_end)
                        @if($subEvent->date_start->format('m') !== $subEvent->date_end->format('m'))
                            {{ $subEvent->date_start->translatedFormat('j M') }} - {{ $subEvent->date_end->translatedFormat('j M Y') }}
                        @else
                            {{ $subEvent->date_start->translatedFormat('j') }} - {{ $subEvent->date_end->translatedFormat('j M Y') }}
                        @endif
                    @elseif($subEvent->date_start)
                        {{ $subEvent->date_start->translatedFormat('j M Y') }}
                    @else
                        TBD
                    @endif
                </span>

                <span class="font-mono text-[10px] tracking-wider uppercase flex items-center gap-1.5 font-medium {{ $subEvent->registration_button_state === 'open' ? 'text-emerald-400' : ($subEvent->registration_button_state === 'closed' ? 'text-rose-400' : 'text-amber-400') }}">
                    <span class="w-1.5 h-1.5 rounded-full inline-block {{ $subEvent->registration_button_state === 'open' ? 'bg-emerald-400' : ($subEvent->registration_button_state === 'closed' ? 'bg-rose-400' : 'bg-amber-400') }}"></span>
                    {{ $subEvent->registration_button_state === 'open' ? 'Pendaftaran Buka' : ($subEvent->registration_button_state === 'closed' ? 'Pendaftaran Tutup' : 'Segera Dibuka') }}
                </span>
            </div>

            <!-- Title & Action Indicator -->
            <div class="flex items-start justify-between gap-3 mb-2">
                <h3 class="font-display text-[19px] sm:text-[21px] font-bold text-ink group-hover:text-ember transition-colors duration-200 uppercase tracking-tight">
                    {{ $subEvent->name }}
                </h3>
                <span class="card-arrow-btn inline-flex items-center justify-center w-8 h-8 rounded-full border border-line bg-black/5 dark:bg-white/10 dark:border-white/15 text-ink-soft shrink-0 transition-all duration-300 group-hover:border-ember group-hover:text-ember group-hover:bg-ember/10" aria-hidden="true" style="width: 2rem; height: 2rem; border-radius: 9999px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" style="width: 1rem; height: 1rem;" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                    </svg>
                </span>
            </div>
            <p class="text-[13.5px] sm:text-[14px] text-ink-soft/90 leading-relaxed mb-6 font-normal">
                {{ Str::limit($subEvent->description, 130) }}
            </p>

            <!-- Minimalist Clean Footer -->
            <div class="pt-4 border-t border-line/40 mt-auto flex items-center justify-between text-[11.5px] font-mono text-ink-soft/80">
                <span>{{ $subEvent->type }}</span>
                @if($subEvent->location)
                <span>{{ $subEvent->location }}</span>
                @endif
            </div>
        </a>
        @empty
        <div class="col-span-full py-16 text-center ios-glass rounded-[24px]">
            <p class="font-mono text-ink-soft uppercase text-[12px] tracking-wider">Acara PARTI {{ session('active_year', config('parti.active_year', 2026)) }} sedang disiapkan.</p>
        </div>
        @endforelse
    </div>
</section>

<section class="py-10 px-4 max-w-[1140px] mx-auto z-10 relative mb-12" id="timeline">
    <div class="ios-glass rounded-[32px] p-8 md:p-14">
        <!-- Section Header Minimalist -->
        <div class="mb-12 md:mb-16">
            <span class="font-mono text-[11px] tracking-[0.25em] uppercase text-ink flex items-center justify-center md:justify-start gap-3 before:content-[''] before:w-[24px] before:h-[1px] before:bg-ember font-bold">
                TIMELINE PARTI {{ session('active_year', config('parti.active_year', 2026)) }}
            </span>
        </div>

        @php
            $itemCount = $timeline->count();
        @endphp

        @if($itemCount > 0)
        <!-- Timeline Flow: Ultra Minimal Line Track -->
        <div class="relative">
            <!-- Continuous line across desktop nodes -->
            @if($itemCount > 1)
            <div class="hidden md:block absolute top-[11px] left-[5%] right-[5%] h-[1px] bg-line/80 dark:bg-white/15 z-0 pointer-events-none"></div>
            @endif

            <!-- Continuous line down mobile nodes -->
            <div class="block md:hidden absolute top-3 bottom-6 left-[7px] w-[1px] bg-line/80 dark:bg-white/15 z-0 pointer-events-none"></div>

            <div class="grid grid-cols-1 {{ $itemCount === 1 ? 'md:grid-cols-1 max-w-md mx-auto' : ($itemCount === 2 ? 'md:grid-cols-2 max-w-3xl mx-auto' : ($itemCount === 3 ? 'md:grid-cols-3' : 'md:grid-cols-4')) }} gap-10 md:gap-8">
                @foreach($timeline as $item)
                @php
                    $desc = trim($item->description ?? '');
                    $isLong = mb_strlen($desc) > 85;
                @endphp
                <div x-data="{ expanded: false }" class="relative pl-8 md:pl-0 z-10 flex flex-col items-start text-left group">
                    <!-- Apple-style minimal node dot -->
                    <div class="absolute left-0 top-[3px] md:relative md:top-auto md:left-auto w-[15px] h-[15px] rounded-full bg-paper border-[2px] border-ember/90 md:mb-6 z-10 transition-transform duration-200 group-hover:scale-125 shadow-sm flex items-center justify-center">
                        <span class="w-[5px] h-[5px] rounded-full bg-ember"></span>
                    </div>

                    <!-- Date Pill & Category -->
                    <div class="flex items-center gap-2 mb-2">
                        <span class="font-mono text-[11px] text-orange-600 dark:text-orange-400 font-semibold tracking-wider">
                            {{ $item->date ? $item->date->translatedFormat('d M Y') : 'TBD' }}
                        </span>
                        @if($item->subEvent)
                        <span class="text-ink-soft/40 text-[11px]">•</span>
                        <span class="font-mono text-[10px] text-ink-soft/70 uppercase tracking-wider">
                            {{ $item->subEvent->type ?? 'Sub-Event' }}
                        </span>
                        @endif
                    </div>

                    <!-- Event Title -->
                    <h4 class="font-display text-[16px] sm:text-[17px] text-ink font-bold uppercase tracking-tight group-hover:text-ember transition-colors duration-200 mb-2 leading-snug">
                        {{ $item->title }}
                    </h4>

                    <!-- Concise Description: Default pendek, klik untuk baca selengkapnya -->
                    <div class="text-[13px] text-ink-soft/90 leading-relaxed font-normal pr-1 mb-2">
                        @if($isLong)
                            <p x-show="!expanded" class="line-clamp-2">
                                {{ $desc }}
                            </p>
                            <p x-show="expanded" x-cloak class="whitespace-pre-line">
                                {{ $desc }}
                            </p>
                            <button type="button"
                                @click="expanded = !expanded"
                                class="inline-flex items-center gap-1 text-[11.5px] font-mono text-ember hover:text-ember-dark underline underline-offset-2 cursor-pointer focus:outline-none mt-1 py-0.5"
                                :aria-expanded="expanded.toString()">
                                <span x-text="expanded ? 'Tutup' : 'Baca lengkap'"></span>
                            </button>
                        @else
                            <p class="whitespace-pre-line">{{ $desc }}</p>
                        @endif
                    </div>

                    <!-- Subtle Action Link (if linked to a sub-event) -->
                    @if($item->subEvent)
                    <a href="{{ route('sub-event.show', $item->subEvent->slug) }}"
                       class="inline-flex items-center gap-1 text-[11.5px] font-mono font-medium text-ink-soft hover:text-ember transition-colors py-1 focus:outline-none focus:underline mt-auto">
                        <span>Lihat detail</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @else
        <!-- Minimal empty state -->
        <div class="py-12 text-center">
            <p class="font-mono text-ink-soft uppercase text-[11px] tracking-widest">
                Timeline pelaksanaan PARTI {{ session('active_year', config('parti.active_year', 2026)) }} belum diumumkan.
            </p>
        </div>
        @endif
    </div>
</section>
@endsection