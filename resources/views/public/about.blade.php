@extends('layouts.public')

@section('title', 'Tentang PARTI ' . session('active_year', config('parti.active_year', 2026)) . ' | Parade Teknik Informatika HIMATIF UMS')
@section('meta_description', 'Pelajari tentang PARTI ' . session('active_year', config('parti.active_year', 2026)) . ' (Parade Teknik Informatika), event tahunan teknologi terbesar dari Himpunan Mahasiswa Teknik Informatika (HIMATIF) Universitas Muhammadiyah Surakarta.')
@section('meta_keywords', 'Tentang PARTI, profil PARTI 2026, profil HIMATIF UMS, parade teknik informatika, event IT Surakarta')

@section('structured_data')
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'AboutPage',
  'name' => 'Tentang PARTI ' . session('active_year', config('parti.active_year', 2026)),
  'description' => 'Profil dan latar belakang penyelenggaraan Parade Teknik Informatika (PARTI) oleh HIMATIF UMS.',
  'url' => request()->url(),
  'mainEntity' => [
    '@id' => url('/') . '#organization'
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
      'name' => 'Tentang PARTI',
      'item' => request()->url()
    ]
  ]
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
<!-- HERO ABOUT HEADER -->
<section class="relative py-12 px-4 max-w-[1140px] mx-auto z-10">
    <div class="ios-glass rounded-[32px] p-8 md:p-14 relative overflow-hidden">
        <div class="flex items-center gap-3.5 mb-6">
            <a href="{{ route('home') }}" class="font-mono text-[11px] text-ink-soft hover:text-ember transition-colors flex items-center gap-2 font-bold uppercase tracking-wider">
                ← Kembali ke Beranda
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-[0.8fr_1.2fr] gap-12 md:gap-16 items-center text-center md:text-left">
            <!-- iOS Glass Logo Panel -->
            <div class="relative max-w-[200px] md:max-w-[280px] mx-auto md:mx-0 w-full flex items-center justify-center p-8 ios-glass rounded-[28px] animate-float">
                <img src="{{ asset('logo.png') }}" alt="Logo PARTI" class="w-full h-auto drop-shadow-sm hover:scale-105 transition-transform duration-500">
            </div>
            
            <div>
                <span class="font-mono text-[11px] tracking-[0.2em] uppercase text-ink flex items-center justify-center md:justify-start gap-2.5 before:content-[''] before:w-[20px] before:h-[1px] before:bg-ember font-bold">
                    Tentang Event
                </span>
                <h1 class="font-display font-bold text-[28px] sm:text-[36px] md:text-[46px] mt-4 mb-2 text-ink uppercase tracking-tight leading-tight">
                    PARADE TEKNIK INFORMATIKA
                </h1>
                <span class="font-mono text-[11px] tracking-[0.15em] text-gold uppercase mb-6 block font-bold">
                    Diselenggarakan oleh Himpunan Mahasiswa Teknik Informatika (HIMATIF) UMS
                </span>
                <p class="text-ink-soft leading-relaxed mb-4 text-[15px] sm:text-[16px]">
                    PARTI (Parade Teknik Informatika) adalah event tahunan terbesar yang diselenggarakan oleh HIMPUNAN MAHASISWA TEKNIK INFORMATIKA UMS. Event ini dirancang sebagai wadah kolaborasi, inovasi, dan ekspresi bagi mahasiswa serta publik di bidang teknologi dan kreatif.
                </p>
                <p class="text-ink-soft leading-relaxed mb-6 text-[15px] sm:text-[16px]">
                    Sebagai platform tahunan yang dinamis, di dalam PARTI terdapat beberapa sub event yang dirancang khusus untuk memadukan kompetensi sains, kreativitas seni, dan kepekaan sosial guna menciptakan sinergi positif yang berkelanjutan bagi masyarakat luas.
                </p>
                <div class="px-6 py-5 ios-glass rounded-[22px] font-display italic text-[15px] sm:text-[17px] text-ink shadow-sm text-left leading-relaxed">
                    “Merajut inovasi teknologi, kreativitas, dan kolaborasi dalam harmoni tahunan.”
                </div>
            </div>
        </div>
    </div>
</section>


<!-- SECTION SPONSOR & MITRA -->
<x-sponsor-section :sponsors="$sponsors" />
@endsection
