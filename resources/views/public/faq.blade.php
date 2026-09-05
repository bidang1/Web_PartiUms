@extends('layouts.public')

@section('title', 'Pertanyaan Sering Diajukan (FAQ) | PARTI HIMATIF UMS')
@section('meta_description', 'Pusat bantuan dan FAQ seputar pendaftaran, sub-acara, dan alur kegiatan PARTI HIMATIF UMS.')
@section('meta_keywords', 'FAQ PARTI, pertanyaan PARTI 2026, pendaftaran lomba PARTI, petunjuk teknis PARTI, HIMATIF UMS')

@section('structured_data')
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'FAQPage',
  'mainEntity' => $faqs->flatten()->map(function($faq) {
    return [
      '@type' => 'Question',
      'name' => $faq->question,
      'acceptedAnswer' => [
        '@type' => 'Answer',
        'text' => $faq->answer
      ]
    ];
  })->values()->all()
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
      'name' => 'Pertanyaan (FAQ)',
      'item' => request()->url()
    ]
  ]
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
<section class="relative py-10 px-4 max-w-[1000px] mx-auto z-10" 
         x-data="{
             activeCategory: 'SEMUA',
             searchQuery: '',
             activeAccordion: null,
             filterMatch(faqCategory, faqQuestion, faqAnswer) {
                 const matchesCat = this.activeCategory === 'SEMUA' || this.activeCategory.toLowerCase() === faqCategory.toLowerCase();
                 const q = this.searchQuery.toLowerCase().trim();
                 const matchesQuery = !q || faqQuestion.toLowerCase().includes(q) || faqAnswer.toLowerCase().includes(q);
                 return matchesCat && matchesQuery;
             }
         }">

    <!-- HERO HEADER CARD -->
    <div class="ios-glass rounded-[28px] p-6 sm:p-10 md:p-12 relative overflow-hidden mb-10 shadow-sm border border-line text-center">
        <!-- Ambient Glow Backdrop -->
        <div class="absolute -top-20 -left-20 w-80 h-80 bg-ember/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-ember/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex items-center justify-between gap-4 mb-6 relative z-10">
            <a href="{{ route('home') }}" class="font-mono text-[11px] text-ink-soft hover:text-ember transition-colors flex items-center gap-1.5 font-bold uppercase tracking-wider">
                ← Kembali ke Beranda
            </a>
            @php $totalCount = $faqs->flatten()->count(); @endphp
            <span class="font-mono text-[10px] bg-ember/10 text-ember border border-ember/20 px-3 py-1 rounded-full font-bold uppercase tracking-wider">
                {{ $totalCount }} PERTANYAAN
            </span>
        </div>

        <div class="max-w-2xl mx-auto relative z-10">
            <span class="font-mono text-[10px] tracking-[0.2em] uppercase text-ember font-bold block mb-2">
                PUSAT BANTUAN & INFORMATION HUB
            </span>
            
            <h1 class="font-display font-bold text-[28px] sm:text-[38px] text-ink uppercase tracking-tight leading-tight mb-3">
                PERTANYAAN SERING DIAJUKAN
            </h1>

            <p class="text-ink-soft text-[14px] sm:text-[15px] leading-relaxed max-w-xl mx-auto mb-8">
                Temukan informasi lengkap terkait pendaftaran, petunjuk teknis, dan alur kegiatan PARTI {{ session('active_year', config('parti.active_year', 2026)) }}.
            </p>

            <!-- LIVE SEARCH BAR -->
            <div class="relative max-w-lg mx-auto mb-6">
                <div class="relative flex items-center">
                    <svg class="w-4 h-4 absolute left-4 text-ink-soft/60 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" 
                           x-model="searchQuery" 
                           placeholder="Cari pertanyaan... (contoh: pendaftaran, biaya)" 
                           class="w-full bg-paper/90 border border-line/80 rounded-full pl-11 pr-10 py-3 text-xs sm:text-sm text-ink placeholder:text-ink-soft/50 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-all shadow-inner">
                    <button x-show="searchQuery" 
                            @click="searchQuery = ''" 
                            x-cloak 
                            class="absolute right-3.5 text-ink-soft/60 hover:text-ember text-[11px] font-mono font-bold uppercase transition-colors">
                        ✕ Clear
                    </button>
                </div>
            </div>

            <!-- CATEGORY FILTER PILLS -->
            @php
                $categories = $faqs->keys()->toArray();
            @endphp
            @if(count($categories) > 0)
                <div class="flex items-center justify-center flex-wrap gap-2">
                    <button @click="activeCategory = 'SEMUA'" 
                            :class="activeCategory === 'SEMUA' ? 'bg-ember text-white border-ember shadow-sm' : 'bg-paper-warm/80 text-ink-soft hover:text-ink border-line/80 hover:border-ember/40'" 
                            class="font-mono text-[10px] font-bold uppercase tracking-wider px-3.5 py-1.5 rounded-full border transition-all cursor-pointer">
                        Semua Kategori
                    </button>
                    @foreach($categories as $cat)
                        <button @click="activeCategory = '{{ $cat }}'" 
                                :class="activeCategory === '{{ $cat }}' ? 'bg-ember text-white border-ember shadow-sm' : 'bg-paper-warm/80 text-ink-soft hover:text-ink border-line/80 hover:border-ember/40'" 
                                class="font-mono text-[10px] font-bold uppercase tracking-wider px-3.5 py-1.5 rounded-full border transition-all cursor-pointer">
                            {{ $cat }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- FAQ LIST CONTAINER -->
    <div class="space-y-8 max-w-3xl mx-auto">
        @if($faqs->isEmpty())
            <div class="ios-glass rounded-[24px] p-10 text-center border border-line">
                <div class="w-12 h-12 rounded-full bg-paper-warm/80 border border-line flex items-center justify-center mx-auto mb-3 text-xl">
                    💡
                </div>
                <h3 class="font-display font-bold text-base text-ink uppercase mb-1">Belum Ada Pertanyaan</h3>
                <p class="text-ink-soft text-xs max-w-xs mx-auto">Data FAQ saat ini belum dipublikasikan. Silakan kembali lagi nanti.</p>
            </div>
        @else
            @php $globalCounter = 0; @endphp
            @foreach($faqs as $category => $categoryFaqs)
                <div class="space-y-3" x-show="activeCategory === 'SEMUA' || activeCategory.toLowerCase() === '{{ strtolower($category) }}'">
                    
                    <!-- Clean Category Pill Header (No Harsh Lines) -->
                    <div class="flex items-center justify-between pt-1 pb-1">
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-paper-warm/90 border border-line/80 rounded-lg shadow-2xs">
                            <span class="w-2 h-2 rounded-full bg-ember inline-block"></span>
                            <span class="font-mono text-[11px] font-bold text-ink uppercase tracking-wider">
                                {{ $category }}
                            </span>
                        </div>
                        <span class="font-mono text-[10px] text-ink-soft/60 font-semibold">
                            {{ count($categoryFaqs) }} Pertanyaan
                        </span>
                    </div>

                    <!-- Accordion Items Group -->
                    <div class="space-y-2.5">
                        @foreach($categoryFaqs as $faq)
                            @php $globalCounter++; @endphp
                            <div x-show="filterMatch({{ json_encode($category) }}, {{ json_encode($faq->question) }}, {{ json_encode($faq->answer) }})"
                                 x-transition:enter="transition ease-out duration-250"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="ios-glass rounded-[18px] border border-line/70 overflow-hidden transition-all duration-200 shadow-2xs"
                                 :class="activeAccordion === {{ $faq->id }} ? 'ring-1 ring-ember/40 border-ember/40 bg-paper-warm/60 shadow-sm' : 'hover:border-ember/30 hover:bg-paper-warm/30'">
                                
                                <button @click="activeAccordion = activeAccordion === {{ $faq->id }} ? null : {{ $faq->id }}" 
                                        class="w-full flex items-center justify-between p-4 sm:p-5 text-left focus:outline-none cursor-pointer group">
                                    
                                    <div class="flex items-center gap-3 pr-3">
                                        <span class="font-display font-bold text-[14px] sm:text-[16px] text-ink group-hover:text-ember transition-colors leading-snug">
                                            {{ $faq->question }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex-shrink-0 w-7 h-7 rounded-full border border-line/80 bg-paper/90 flex items-center justify-center text-ink transition-all duration-300 group-hover:border-ember/50"
                                         :class="activeAccordion === {{ $faq->id }} ? 'rotate-180 bg-ember text-white border-ember shadow-2xs' : ''">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </button>
                                
                                <div x-show="activeAccordion === {{ $faq->id }}" 
                                     x-collapse
                                     x-cloak>
                                    <div class="px-4 sm:px-5 pb-5 pt-2 text-ink-soft text-[13px] sm:text-[14px] leading-relaxed border-t border-line/40 font-body">
                                        {!! nl2br(e($faq->answer)) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- SEARCH NO RESULTS FALLBACK -->
            <div x-show="searchQuery && $el.parentElement.querySelectorAll('[x-show*=\'filterMatch\']:not([style*=\'display: none\'])').length === 0"
                 x-cloak
                 class="ios-glass rounded-[24px] p-10 text-center border border-line">
                <div class="w-12 h-12 rounded-full bg-paper-warm/80 border border-line flex items-center justify-center mx-auto mb-3 text-xl">
                    🔍
                </div>
                <h3 class="font-display font-bold text-base text-ink uppercase mb-1">Hasil Tidak Ditemukan</h3>
                <p class="text-ink-soft text-xs max-w-xs mx-auto">Tidak ada pertanyaan dengan kata kunci "<span x-text="searchQuery" class="font-semibold text-ember"></span>". Coba kata kunci lain.</p>
            </div>
        @endif
    </div>

    <!-- CONTACT HELP BANNER (Generous spacing & sleek compact design) -->
    <div style="margin-top: 70px; margin-bottom: 40px;" class="mt-16 sm:mt-20 mb-10 max-w-3xl mx-auto ios-glass rounded-[24px] p-6 sm:p-8 text-center relative overflow-hidden border border-line/80 shadow-sm">
        <div class="max-w-xl mx-auto">
            <span class="font-mono text-[9px] tracking-[0.2em] uppercase text-ember font-bold block mb-1.5">MASIH PERLU BANTUAN?</span>
            <h3 class="font-display font-bold text-lg sm:text-xl text-ink uppercase mb-2">HUBUNGI PANITIA PARTI</h3>
            <p class="text-ink-soft text-xs sm:text-sm mb-5 leading-relaxed">Apabila Anda membutuhkan informasi lebih lanjut yang belum tercantum di atas, silakan hubungi tim panitia kami.</p>
            
            <div class="flex items-center justify-center gap-3 flex-wrap">
                @if(config('parti.socials.parti.instagram'))
                    <a href="{{ config('parti.socials.parti.instagram') }}" target="_blank" rel="noopener noreferrer" 
                       class="font-mono text-[10px] font-bold tracking-wider uppercase bg-ink text-paper px-5 py-2.5 rounded-full transition-all hover:opacity-90 shadow-2xs flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"></path>
                            <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                        </svg>
                        Instagram @PARTI
                    </a>
                @endif
                <a href="{{ route('home') }}#sub-acara" 
                   class="font-mono text-[10px] font-bold tracking-wider uppercase bg-paper-warm border border-line text-ink px-5 py-2.5 rounded-full transition-all hover:border-ember hover:text-ember shadow-2xs">
                    Jelajahi Sub Acara ↗
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
