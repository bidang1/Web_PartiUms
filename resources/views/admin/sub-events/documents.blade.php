@extends('layouts.admin')

@section('title', 'Dokumen Template | ' . $subEvent->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6 text-left" x-data="{ editingDocId: {{ old('_edit_doc_id', 'null') }} }">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.sub-events.index') }}" class="font-mono text-[11px] text-ink-soft hover:text-ember font-bold uppercase tracking-wider">
            ← Kembali ke Sub Acara
        </a>
    </div>

    <!-- Header -->
    <div>
        <h1 class="font-display font-bold text-2xl text-ink uppercase tracking-wide">Dokumen Template</h1>
        <p class="text-ink-soft text-sm mt-1">Unggah dan kelola dokumen persyaratan (PDF/DOCX) untuk sub-acara <strong>{{ $subEvent->name }}</strong>.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-[1fr_1.5fr] gap-6 items-start">
        <!-- Left: Upload New Document Form -->
        <div class="bg-white border border-line rounded-[6px] p-6 shadow-sm space-y-4">
            <h3 class="font-display font-bold text-base text-ink uppercase tracking-wide border-b border-line pb-2">Unggah Baru</h3>
            
            <form method="POST" action="{{ route('admin.documents.store', $subEvent) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <div>
                    <label for="label" class="block font-mono text-[10px] tracking-wider uppercase text-ink-soft mb-1 font-bold">Label Dokumen</label>
                    <input id="label" name="label" type="text" value="{{ old('label') }}" required placeholder="Contoh: Formulir Pendaftaran Tim"
                           class="block w-full border border-line rounded-[2px] px-3 py-2 text-xs bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember" />
                    @error('label')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="file" class="block font-mono text-[10px] tracking-wider uppercase text-ink-soft mb-1 font-bold">File Dokumen (PDF/DOCX)</label>
                    <input id="file" name="file" type="file" required
                           class="block w-full text-xs text-ink-soft file:mr-3 file:py-1.5 file:px-3 file:rounded-[2px] file:border file:border-line file:text-xs file:font-semibold file:bg-paper-warm/40 file:text-ink-soft hover:file:bg-paper-warm cursor-pointer" />
                    <p class="text-[9px] text-ink-soft/60 mt-1">Maksimal {{ config('parti.max_upload_size_mb', 10) }} MB.</p>
                    @error('file')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="order" class="block font-mono text-[10px] tracking-wider uppercase text-ink-soft mb-1 font-bold">Urutan Tampil</label>
                    <input id="order" name="order" type="number" value="{{ old('order', 0) }}" min="0" required
                           class="block w-full border border-line rounded-[2px] px-3 py-2 text-xs bg-paper-warm/20 focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember" />
                    @error('order')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full text-center bg-gradient-to-r from-ember to-ember-dark text-white font-semibold text-xs px-5 py-2.5 rounded-[2px] transition-premium hover:shadow-[0_8px_16px_-4px_rgba(226,101,11,0.4)]">
                        Unggah Dokumen
                    </button>
                </div>
            </form>
        </div>

        <!-- Right: Documents List -->
        <div class="bg-white border border-line rounded-[6px] shadow-sm overflow-hidden space-y-4">
            <div class="p-6 border-b border-line">
                <h3 class="font-display font-bold text-base text-ink uppercase tracking-wide">Daftar Dokumen</h3>
            </div>

            <div class="divide-y divide-line/60">
                @forelse($documents as $doc)
                    <div class="p-6 space-y-4">
                        <!-- View Mode -->
                        <div x-show="editingDocId !== {{ $doc->id }}" class="flex items-start justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-[9px] bg-ember/10 text-ember px-2 py-0.5 rounded font-bold uppercase border border-ember/20">
                                        {{ $doc->file_type }}
                                    </span>
                                    <span class="font-semibold text-ink text-sm">{{ $doc->label }}</span>
                                    <span class="text-xs text-ink-soft/60">({{ $doc->file_size_formatted }})</span>
                                </div>
                                <div class="text-[10px] text-ink-soft/60 font-mono">
                                    <span>Urutan: {{ $doc->order }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3.5">
                                <button @click="editingDocId = {{ $doc->id }}" class="font-mono text-[11px] text-ember hover:text-ember-dark font-bold uppercase tracking-wider">
                                    ✏️ Edit
                                </button>
                                <form method="POST" action="{{ route('admin.documents.destroy', $doc->id) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-mono text-[11px] text-rose-600 hover:text-rose-800 font-bold uppercase tracking-wider">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Edit Mode Inline Form -->
                        <div x-show="editingDocId === {{ $doc->id }}" x-cloak class="p-4 border border-line bg-paper-warm/30 rounded-[2px] space-y-4">
                            <form method="POST" action="{{ route('admin.documents.update', $doc->id) }}" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="_edit_doc_id" value="{{ $doc->id }}">

                                @if(old('_edit_doc_id') == $doc->id && $errors->any())
                                    <div class="p-2.5 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-[2px]">
                                        <ul class="list-disc list-inside space-y-0.5">
                                            @foreach($errors->all() as $err)
                                                <li>{{ $err }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                
                                <div>
                                    <label for="edit_label_{{ $doc->id }}" class="block font-mono text-[9px] tracking-wider uppercase text-ink-soft mb-1 font-bold">Label Dokumen</label>
                                    <input id="edit_label_{{ $doc->id }}" name="label" type="text" value="{{ old('label', $doc->label) }}" required
                                           class="block w-full border border-line rounded-[2px] px-2.5 py-1.5 text-xs bg-white focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember" />
                                </div>

                                <div>
                                    <label for="edit_file_{{ $doc->id }}" class="block font-mono text-[9px] tracking-wider uppercase text-ink-soft mb-1 font-bold">Ganti File (Opsional)</label>
                                    <input id="edit_file_{{ $doc->id }}" name="file" type="file"
                                           class="block w-full text-xs text-ink-soft file:mr-2 file:py-1 file:px-2.5 file:rounded-[2px] file:border file:border-line file:text-[11px] file:font-semibold file:bg-white file:text-ink-soft hover:file:bg-paper-warm cursor-pointer" />
                                </div>

                                <div>
                                    <label for="edit_order_{{ $doc->id }}" class="block font-mono text-[9px] tracking-wider uppercase text-ink-soft mb-1 font-bold">Urutan</label>
                                    <input id="edit_order_{{ $doc->id }}" name="order" type="number" value="{{ old('order', $doc->order) }}" min="0" required
                                           class="block w-full border border-line rounded-[2px] px-2.5 py-1.5 text-xs bg-white focus:outline-none focus:border-ember focus:ring-1 focus:ring-ember" />
                                </div>

                                <div class="flex justify-end gap-2 text-xs pt-1">
                                    <button type="button" @click="editingDocId = null" class="px-3 py-1.5 border border-line rounded-[2px] bg-white text-ink-soft hover:bg-paper-warm/30">
                                        Batal
                                    </button>
                                    <button type="submit" class="px-3 py-1.5 bg-ember hover:bg-ember-dark text-white rounded-[2px] font-semibold">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-ink-soft/60">
                        Belum ada dokumen persyaratan yang diunggah untuk sub-acara ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

