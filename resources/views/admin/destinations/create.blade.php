@extends('layouts.admin')

@section('title', 'Tambah Destinasi Baru')
@section('header_title', 'Tambah Destinasi Baru')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map-picker { height: 260px; width: 100%; border-radius: 0.5rem; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-[#12181F]">Formulir Destinasi Baru</h2>
            <p class="text-xs text-[#4A5A6B]">Tambahkan data destinasi wisata atau UMKM baru ke dalam sistem GIS</p>
        </div>
        <a href="{{ route('admin.destinations.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg border border-[#D8E1EA] text-[#4A5A6B] hover:bg-gray-100 transition-colors">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <form method="POST" action="{{ route('admin.destinations.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Informasi Utama -->
        <div class="bg-white p-6 rounded-xl border border-[#D8E1EA] shadow-sm space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-[#123B6D] border-b border-gray-100 pb-2">
                1. Informasi Dasar
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Nama Destinasi -->
                <div class="sm:col-span-2">
                    <label for="name" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Nama Destinasi / Tempat Usaha <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Bukit Blitarejo / Kopi Pringsewu" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>

                <!-- Kategori -->
                <div>
                    <label for="category" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select id="category" name="category" required class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E] bg-white">
                        <option value="Wisata" {{ old('category') === 'Wisata' ? 'selected' : '' }}>Wisata</option>
                        <option value="UMKM" {{ old('category') === 'UMKM' ? 'selected' : '' }}>UMKM</option>
                    </select>
                </div>

                <!-- Status Operasional -->
                <div>
                    <label for="status" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Status Operasional <span class="text-red-500">*</span></label>
                    <select id="status" name="status" required class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E] bg-white">
                        <option value="Buka" {{ old('status', 'Buka') === 'Buka' ? 'selected' : '' }}>Buka</option>
                        <option value="Tutup" {{ old('status') === 'Tutup' ? 'selected' : '' }}>Tutup</option>
                        <option value="Musiman" {{ old('status') === 'Musiman' ? 'selected' : '' }}>Musiman</option>
                    </select>
                </div>

                <!-- Kecamatan -->
                <div>
                    <label for="district" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Kecamatan <span class="text-red-500">*</span></label>
                    <select id="district" name="district" required class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E] bg-white">
                        <option value="">Pilih Kecamatan</option>
                        @foreach($districts as $d)
                            <option value="{{ $d }}" {{ old('district') === $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Lokasi Ringkas -->
                <div>
                    <label for="location" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Lokasi Ringkas <span class="text-red-500">*</span></label>
                    <input type="text" id="location" name="location" value="{{ old('location') }}" required placeholder="Contoh: Blitarejo, Gadingrejo" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>

                <!-- Alamat Lengkap -->
                <div class="sm:col-span-2">
                    <label for="address" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Alamat Lengkap</label>
                    <textarea id="address" name="address" rows="2" placeholder="Jl. Raya ..., Desa ..., RT/RW ..." class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">{{ old('address') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Detail Operasional & Kontak -->
        <div class="bg-white p-6 rounded-xl border border-[#D8E1EA] shadow-sm space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-[#123B6D] border-b border-gray-100 pb-2">
                2. Detail Operasional & Kontak
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Jam Buka -->
                <div>
                    <label for="open_hours" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Jam Operasional</label>
                    <input type="text" id="open_hours" name="open_hours" value="{{ old('open_hours', '08:00 - 17:00 WIB') }}" placeholder="Contoh: 08:00 - 17:00 WIB" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>

                <!-- Tarif Weekday -->
                <div>
                    <label for="weekday_price" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Tarif Hari Kerja (Rp)</label>
                    <input type="number" id="weekday_price" name="weekday_price" value="{{ old('weekday_price') }}" min="0" placeholder="0 untuk gratis" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>

                <!-- Tarif Weekend -->
                <div>
                    <label for="weekend_price" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Tarif Akhir Pekan (Rp)</label>
                    <input type="number" id="weekend_price" name="weekend_price" value="{{ old('weekend_price') }}" min="0" placeholder="0 untuk gratis" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>

                <!-- Telepon / WhatsApp -->
                <div>
                    <label for="phone" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Nomor Telepon / WA</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>

                <!-- Rating -->
                <div>
                    <label for="rating" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Rating Awal (0 - 5.0)</label>
                    <input type="number" step="0.1" min="0" max="5" id="rating" name="rating" value="{{ old('rating', 4.5) }}" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>

                <!-- Jumlah Ulasan -->
                <div>
                    <label for="review_count" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Jumlah Ulasan Awal</label>
                    <input type="number" min="0" id="review_count" name="review_count" value="{{ old('review_count', 0) }}" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>
            </div>

            <!-- Tags -->
            <div>
                <label for="tags" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Label / Tag (Pisahkan dengan tanda koma)</label>
                <input type="text" id="tags" name="tags" value="{{ old('tags') }}" placeholder="Contoh: Alam, Sunset, Ramah Keluarga, Spot Foto" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
            </div>
        </div>

        <!-- Deskripsi -->
        <div class="bg-white p-6 rounded-xl border border-[#D8E1EA] shadow-sm space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-[#123B6D] border-b border-gray-100 pb-2">
                3. Deskripsi Destinasi
            </h3>

            <div>
                <label for="description" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Deskripsi Singkat (Ringkasan pada kartu)</label>
                <textarea id="description" name="description" rows="2" placeholder="Tuliskan 1-2 kalimat ringkas tentang keunikan destinasi ini..." class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="long_description" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Deskripsi Lengkap (Halaman detail)</label>
                <textarea id="long_description" name="long_description" rows="5" placeholder="Jelaskan fasilitas, daya tarik utama, akses jalan, dan informasi penting lainnya..." class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">{{ old('long_description') }}</textarea>
            </div>
        </div>

        <!-- Foto & Gambar -->
        <div class="bg-white p-6 rounded-xl border border-[#D8E1EA] shadow-sm space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-[#123B6D] border-b border-gray-100 pb-2">
                4. Foto & Media
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="image_file" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Upload Berkas Foto</label>
                    <input type="file" id="image_file" name="image_file" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#EEF3F8] file:text-[#0E7C9E] hover:file:bg-[#dce7f2]">
                    <p class="text-[11px] text-gray-400 mt-1">Format: JPG, PNG, WEBP. Maks 4MB.</p>
                </div>

                <div>
                    <label for="image_url" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Atau Gunakan Tautan URL Gambar</label>
                    <input type="url" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/foto.jpg" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>
            </div>
        </div>

        <!-- Koordinat GIS & Peta -->
        <div class="bg-white p-6 rounded-xl border border-[#D8E1EA] shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                <h3 class="text-sm font-bold uppercase tracking-wider text-[#123B6D]">
                    5. Titik Koordinat Peta (GIS)
                </h3>
                <span class="text-[11px] text-gray-500">Klik pada peta di bawah untuk memilih koordinat otomatis</span>
            </div>

            <div id="map-picker"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="latitude" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Latitude</label>
                    <input type="number" step="any" id="latitude" name="latitude" value="{{ old('latitude', -5.3582) }}" placeholder="-5.3582" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>

                <div>
                    <label for="longitude" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Longitude</label>
                    <input type="number" step="any" id="longitude" name="longitude" value="{{ old('longitude', 104.9754) }}" placeholder="104.9754" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.destinations.index') }}" class="px-5 py-2.5 rounded-lg border border-[#D8E1EA] text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#0E7C9E] hover:bg-[#0b627d] text-white text-sm font-semibold shadow-md transition-colors">
                Simpan Destinasi
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const defaultLat = parseFloat(document.getElementById('latitude').value) || -5.3582;
        const defaultLng = parseFloat(document.getElementById('longitude').value) || 104.9754;

        const map = L.map('map-picker').setView([defaultLat, defaultLng], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

        function updateInputs(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
        }

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateInputs(e.latlng.lat, e.latlng.lng);
        });

        marker.on('dragend', function(e) {
            const pos = marker.getLatLng();
            updateInputs(pos.lat, pos.lng);
        });
    });
</script>
@endpush
