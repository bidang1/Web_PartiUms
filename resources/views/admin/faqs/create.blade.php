@extends('layouts.admin')

@section('title', 'Tambah FAQ | PARTI ' . (session('active_year', config('parti.active_year', 2026))))

@section('content')
<div class="space-y-6 text-left max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-display font-bold text-2xl text-ink uppercase tracking-wide">Tambah FAQ Baru</h1>
            <p class="text-ink-soft text-sm mt-1">Buat data Q&A baru untuk ditampilkan di halaman publik.</p>
        </div>
        <a href="{{ route('admin.faqs.index') }}" class="font-mono text-[11px] text-ink-soft hover:text-ink font-bold uppercase tracking-wider flex items-center gap-1.5 border border-line hover:border-ink/30 px-3 py-1.5 rounded-[2px] transition-all bg-white">
            ← Kembali ke Daftar
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white border border-line rounded-[6px] shadow-sm overflow-hidden">
        <div class="p-6 border-b border-line">
            <h3 class="font-display font-bold text-base text-ink uppercase tracking-wide">Formulir Data FAQ</h3>
        </div>
        
        <form action="{{ route('admin.faqs.store') }}" method="POST" class="p-6 md:p-8 space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kategori -->
                <div class="space-y-1.5">
                    <label for="category" class="block font-mono text-[10px] tracking-widest uppercase text-ink-soft/75 font-bold">Kategori FAQ</label>
                    <input type="text" id="category" name="category" value="{{ old('category', 'Umum') }}" 
                           class="w-full bg-paper-warm border border-line rounded-[2px] px-4 py-2.5 text-sm text-ink focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" 
                           placeholder="Umum, Lomba, Pendaftaran" required>
                    @error('category') <p class="text-xs text-rose-500 mt-1 font-mono">{{ $message }}</p> @enderror
                </div>
                
                <!-- Order -->
                <div class="space-y-1.5">
                    <label for="order" class="block font-mono text-[10px] tracking-widest uppercase text-ink-soft/75 font-bold">Urutan Tampil</label>
                    <input type="number" id="order" name="order" value="{{ old('order', 1) }}" 
                           class="w-full bg-paper-warm border border-line rounded-[2px] px-4 py-2.5 text-sm text-ink focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" 
                           placeholder="1" min="1" required>
                    <p class="text-[11px] text-ink-soft/60 mt-1">Angka terkecil akan tampil paling atas.</p>
                    @error('order') <p class="text-xs text-rose-500 mt-1 font-mono">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Pertanyaan -->
            <div class="space-y-1.5">
                <label for="question" class="block font-mono text-[10px] tracking-widest uppercase text-ink-soft/75 font-bold">Pertanyaan</label>
                <input type="text" id="question" name="question" value="{{ old('question') }}" 
                       class="w-full bg-paper-warm border border-line rounded-[2px] px-4 py-2.5 text-sm text-ink focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" 
                       placeholder="Masukkan pertanyaan yang sering diajukan..." required>
                @error('question') <p class="text-xs text-rose-500 mt-1 font-mono">{{ $message }}</p> @enderror
            </div>

            <!-- Jawaban -->
            <div class="space-y-1.5">
                <label for="answer" class="block font-mono text-[10px] tracking-widest uppercase text-ink-soft/75 font-bold">Jawaban</label>
                <textarea id="answer" name="answer" rows="6" 
                          class="w-full bg-paper-warm border border-line rounded-[2px] px-4 py-3 text-sm text-ink focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember transition-colors" 
                          placeholder="Masukkan jawaban yang lengkap..." required>{{ old('answer') }}</textarea>
                @error('answer') <p class="text-xs text-rose-500 mt-1 font-mono">{{ $message }}</p> @enderror
            </div>
            
            <!-- Status Aktif -->
            <div class="space-y-1.5">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-10 h-5 bg-line/50 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-ember relative"></div>
                    <span class="font-mono text-xs tracking-wide text-ink font-bold uppercase group-hover:text-ember transition-colors">Tampilkan FAQ di Website</span>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-line/50 flex justify-end">
                <button type="submit" class="bg-gradient-to-r from-ember to-ember-dark text-white font-semibold text-xs px-8 py-3 rounded-[2px] transition-premium hover:shadow-[0_10px_20px_-5px_rgba(226,101,11,0.4)]">
                    SIMPAN FAQ
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
