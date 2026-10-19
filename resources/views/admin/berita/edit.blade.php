@extends('layouts.admin')

@section('title', 'Edit Berita: ' . $berita->title)
@section('header_title', 'Sunting Berita & Artikel')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-[#12181F]">Sunting Artikel Berita</h2>
            <p class="text-xs text-[#4A5A6B]">Perbarui judul, tanggal publikasi, isi konten, atau gambar artikel</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('berita.detail', $berita->slug) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg bg-[#EEF3F8] text-[#0E7C9E] hover:bg-[#dce7f2] transition-colors">
                Lihat di Web &rarr;
            </a>
            <a href="{{ route('admin.berita.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg border border-[#D8E1EA] text-[#4A5A6B] hover:bg-gray-100 transition-colors">
                &larr; Kembali
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.berita.update', $berita) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white p-6 rounded-xl border border-[#D8E1EA] shadow-sm space-y-4">
            <!-- Judul Berita -->
            <div>
                <label for="title" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Judul Berita / Artikel <span class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title', $berita->title) }}" required class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
            </div>

            <!-- Slug & Tanggal Publikasi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="slug" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Slug URL</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $berita->slug) }}" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E] font-mono text-xs">
                </div>

                <div>
                    <label for="published_at" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Waktu Publikasi</label>
                    <input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at', $berita->published_at ? $berita->published_at->format('Y-m-d\TH:i') : '') }}" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E] bg-white">
                </div>
            </div>

            <!-- Foto Berita Saat Ini & Ganti Foto -->
            <div class="pt-2 border-t border-gray-100">
                <p class="text-xs font-bold text-[#123B6D] uppercase tracking-wider mb-3">Foto Utama Berita</p>
                
                @if($berita->image)
                    <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg border border-gray-200 mb-4">
                        <img src="{{ $berita->image }}" alt="{{ $berita->title }}" class="w-24 h-16 rounded-lg object-cover border border-gray-300">
                        <div>
                            <p class="text-xs font-bold text-gray-800">Foto Saat Ini</p>
                            <p class="text-[11px] text-gray-500 break-all max-w-md">{{ $berita->getRawOriginal('image') }}</p>
                            <p class="text-[10px] text-gray-400 mt-1">Unggah berkas baru atau masukkan tautan gambar baru di bawah jika ingin mengganti.</p>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="image_file" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Upload Berkas Foto Baru</label>
                        <input type="file" id="image_file" name="image_file" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#EEF3F8] file:text-[#0E7C9E] hover:file:bg-[#dce7f2]">
                        <p class="text-[11px] text-gray-400 mt-1">Format: JPG, PNG, WEBP. Maks 4MB.</p>
                    </div>

                    <div>
                        <label for="image_url" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Atau Gunakan Tautan URL Gambar Baru</label>
                        <input type="url" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/foto-berita.jpg" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                    </div>
                </div>
            </div>

            <!-- Konten Artikel -->
            <div class="pt-2 border-t border-gray-100">
                <label for="content" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Isi Artikel / Konten Berita <span class="text-red-500">*</span></label>
                <textarea id="content" name="content" rows="14" required class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E] leading-relaxed">{{ old('content', $berita->content) }}</textarea>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.berita.index') }}" class="px-5 py-2.5 rounded-lg border border-[#D8E1EA] text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#0E7C9E] hover:bg-[#0b627d] text-white text-sm font-semibold shadow-md transition-colors">
                Perbarui Berita
            </button>
        </div>
    </form>
</div>
@endsection
