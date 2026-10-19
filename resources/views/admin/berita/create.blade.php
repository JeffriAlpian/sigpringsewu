@extends('layouts.admin')

@section('title', 'Tulis Berita Baru')
@section('header_title', 'Tulis Berita Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-[#12181F]">Formulir Berita / Artikel</h2>
            <p class="text-xs text-[#4A5A6B]">Tulis artikel baru untuk menginformasikan destinasi, agenda dinas, dan UMKM</p>
        </div>
        <a href="{{ route('admin.berita.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg border border-[#D8E1EA] text-[#4A5A6B] hover:bg-gray-100 transition-colors">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <form method="POST" action="{{ route('admin.berita.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="bg-white p-6 rounded-xl border border-[#D8E1EA] shadow-sm space-y-4">
            <!-- Judul Berita -->
            <div>
                <label for="title" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Judul Berita / Artikel <span class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="Contoh: Pesona Wisata Alam Talang Indah Jadi Primadona Wisatawan" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
            </div>

            <!-- Slug & Tanggal Publikasi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="slug" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Slug URL (Dihasilkan otomatis jika kosong)</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}" placeholder="pesona-wisata-alam-talang-indah" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E] font-mono text-xs">
                </div>

                <div>
                    <label for="published_at" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Waktu Publikasi</label>
                    <input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E] bg-white">
                </div>
            </div>

            <!-- Gambar / Foto Berita -->
            <div class="pt-2 border-t border-gray-100">
                <p class="text-xs font-bold text-[#123B6D] uppercase tracking-wider mb-3">Foto Utama Berita</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="image_file" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Upload Berkas Foto</label>
                        <input type="file" id="image_file" name="image_file" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#EEF3F8] file:text-[#0E7C9E] hover:file:bg-[#dce7f2]">
                        <p class="text-[11px] text-gray-400 mt-1">Format: JPG, PNG, WEBP. Maks 4MB.</p>
                    </div>

                    <div>
                        <label for="image_url" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Atau Gunakan Tautan URL Gambar</label>
                        <input type="url" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/foto-berita.jpg" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                    </div>
                </div>
            </div>

            <!-- Konten Artikel -->
            <div class="pt-2 border-t border-gray-100">
                <label for="content" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Isi Artikel / Konten Berita <span class="text-red-500">*</span></label>
                <textarea id="content" name="content" rows="12" required placeholder="Tuliskan isi berita secara lengkap di sini..." class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E] leading-relaxed">{{ old('content') }}</textarea>
                <p class="text-[11px] text-gray-400 mt-1">Gunakan baris baru atau spasi antar paragraf untuk keterbacaan yang optimal di halaman pembaca.</p>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.berita.index') }}" class="px-5 py-2.5 rounded-lg border border-[#D8E1EA] text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#0E7C9E] hover:bg-[#0b627d] text-white text-sm font-semibold shadow-md transition-colors">
                Terbitkan Berita
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');

        titleInput.addEventListener('input', function() {
            if (!slugInput.dataset.manual) {
                slugInput.value = titleInput.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }
        });

        slugInput.addEventListener('input', function() {
            slugInput.dataset.manual = 'true';
        });
    });
</script>
@endpush
