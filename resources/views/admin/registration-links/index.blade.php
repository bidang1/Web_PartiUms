@extends('layouts.admin')

@section('title', 'Tautan Pendaftaran | PARTI ' . $year)

@section('content')
<div class="space-y-6 text-left">
    <!-- Header -->
    <div>
        <h1 class="font-display font-bold text-2xl text-ink uppercase tracking-wide">Tautan Pendaftaran</h1>
        <p class="text-ink-soft text-sm mt-1">Perbarui tautan Google Form pendaftaran per sub-acara untuk tahun aktif {{ $year }}.</p>
    </div>

    <!-- Sub Events Links List -->
    <div class="bg-white border border-line rounded-[6px] shadow-sm overflow-hidden">
        <div class="p-6 border-b border-line">
            <h3 class="font-display font-bold text-base text-ink uppercase tracking-wide">Daftar Sub Acara</h3>
        </div>

        <div class="divide-y divide-line/60">
            @forelse($subEvents as $subEvent)
                <div class="p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <!-- Left: Sub Event Info -->
                    <div class="space-y-2 lg:max-w-md">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h4 class="font-display font-bold text-lg text-ink uppercase">{{ $subEvent->name }}</h4>
                            <span class="font-mono text-[9px] tracking-wider px-2 py-0.5 rounded-[3px] border font-bold uppercase {{ $subEvent->status === 'PUBLISHED' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($subEvent->status === 'CLOSED' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-amber-50 text-amber-700 border-amber-200') }}">
                                {{ $subEvent->status }}
                            </span>
                        </div>
                        <p class="text-xs text-ink-soft/80 italic">{{ $subEvent->tagline }}</p>
                        
                        <!-- Last update metadata -->
                        @if($subEvent->gform_updated_by)
                            <div class="font-mono text-[10px] text-ink-soft/60 pt-2 flex flex-col gap-0.5">
                                <span>Diperbarui oleh: <span class="font-semibold">{{ $subEvent->gformUpdatedBy?->name ?? 'Pengguna Dihapus' }}</span></span>
                                <span>Pada: <span class="font-semibold">{{ $subEvent->gform_updated_at?->translatedFormat('j M Y, H:i') ?? '-' }}</span></span>
                            </div>
                        @else
                            <div class="font-mono text-[10px] text-ink-soft/40 pt-2">
                                Belum pernah diperbarui.
                            </div>
                        @endif
                    </div>

                    <!-- Right: Form Update Link -->
                    <div class="flex-1 lg:max-w-xl">
                        <form method="POST" action="{{ route('admin.registration-links.update', $subEvent) }}" class="space-y-4"
                              x-data="{ links: @js(old('gform_links', $subEvent->gform_link ?? [])) }">
                            @csrf
                            @method('PUT')
                            
                            <template x-for="(link, index) in links" :key="index">
                                <div class="flex flex-col sm:flex-row gap-2 items-start">
                                    <div class="w-full sm:w-1/3">
                                        <input type="text" x-model="link.label" :name="`gform_links[${index}][label]`" 
                                               placeholder="Label (Contoh: Umum)" 
                                               required
                                               class="w-full border border-line rounded-[2px] px-3.5 py-2 text-sm bg-paper-warm/10 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" />
                                    </div>
                                    <div class="flex-grow relative flex gap-2">
                                        <input type="url" x-model="link.url" :name="`gform_links[${index}][url]`" 
                                               placeholder="https://docs.google.com/forms/d/..." 
                                               required
                                               class="w-full border border-line rounded-[2px] px-3.5 py-2 text-sm bg-paper-warm/10 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" />
                                        
                                        <button type="button" @click="links.splice(index, 1)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-[2px] transition-colors" title="Hapus Tautan">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <div class="flex flex-col sm:flex-row gap-2 justify-between items-center">
                                <button type="button" @click="links.push({label: '', url: ''})" class="text-xs text-ember hover:text-ember-dark font-semibold px-2 py-1 flex items-center gap-1 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    Tambah Tautan
                                </button>

                                <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-ember to-ember-dark text-white font-semibold text-xs px-5 py-2 rounded-[2px] transition-premium hover:shadow-[0_8px_16px_-4px_rgba(226,101,11,0.4)] whitespace-nowrap">
                                    Simpan Tautan
                                </button>
                            </div>
                            
                            @if($errors->has('gform_links') || $errors->has('gform_links.*'))
                                <div class="text-xs text-rose-600 font-medium">
                                    Terdapat kesalahan pada tautan yang Anda masukkan. Pastikan label terisi dan URL berupa Google Form yang valid.
                                </div>
                            @endif

                            <!-- Preview link -->
                            @if(is_array($subEvent->gform_link) && count($subEvent->gform_link) > 0)
                                <div class="text-[11px] flex flex-col gap-1 mt-3 border-t border-line/50 pt-2">
                                    @foreach($subEvent->gform_link as $link)
                                        <div class="flex items-center gap-1">
                                            <span class="text-emerald-600">🔗</span>
                                            <span class="font-semibold text-ink-soft">{{ $link['label'] ?? 'Link' }}:</span>
                                            <a href="{{ $link['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="text-ink-soft hover:text-ember underline truncate block max-w-sm sm:max-w-md font-mono">
                                                {{ $link['url'] ?? '' }}
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-[11px] text-amber-600 flex items-center gap-1 font-mono mt-2">
                                    <span>⏳</span>
                                    <span>Tautan kosong. Tombol di web publik akan berstatus "Segera Dibuka".</span>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-ink-soft/60">
                    Belum ada sub-acara terdaftar untuk tahun {{ $year }}. Hubungi Superadmin untuk membuat sub-acara.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

