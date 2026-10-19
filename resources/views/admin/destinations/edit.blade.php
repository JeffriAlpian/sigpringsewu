@extends('layouts.admin')

@section('title', 'Edit Destinasi: ' . $destination->name)
@section('header_title', 'Sunting Destinasi')

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
            <h2 class="text-xl font-bold text-[#12181F]">Sunting Destinasi: {{ $destination->name }}</h2>
            <p class="text-xs text-[#4A5A6B]">Perbarui data informasi destinasi wisata atau UMKM pada sistem</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('detail', $destination->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg bg-[#EEF3F8] text-[#0E7C9E] hover:bg-[#dce7f2] transition-colors">
                Lihat di Web &rarr;
            </a>
            <a href="{{ route('admin.destinations.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg border border-[#D8E1EA] text-[#4A5A6B] hover:bg-gray-100 transition-colors">
                &larr; Kembali
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.destinations.update', $destination) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Informasi Utama -->
        <div class="bg-white p-6 rounded-xl border border-[#D8E1EA] shadow-sm space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-[#123B6D] border-b border-gray-100 pb-2">
                1. Informasi Dasar
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Nama Destinasi -->
                <div class="sm:col-span-2">
                    <label for="name" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Nama Destinasi / Tempat Usaha <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $destination->name) }}" required class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>

                <!-- Kategori -->
                <div>
                    <label for="category" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select id="category" name="category" required class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E] bg-white">
                        <option value="Wisata" {{ old('category', $destination->category) === 'Wisata' ? 'selected' : '' }}>Wisata</option>
                        <option value="UMKM" {{ old('category', $destination->category) === 'UMKM' ? 'selected' : '' }}>UMKM</option>
                    </select>
                </div>

                <!-- Status Operasional -->
                <div>
                    <label for="status" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Status Operasional <span class="text-red-500">*</span></label>
                    <select id="status" name="status" required class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E] bg-white">
                        <option value="Buka" {{ old('status', $destination->status) === 'Buka' ? 'selected' : '' }}>Buka</option>
                        <option value="Tutup" {{ old('status', $destination->status) === 'Tutup' ? 'selected' : '' }}>Tutup</option>
                        <option value="Musiman" {{ old('status', $destination->status) === 'Musiman' ? 'selected' : '' }}>Musiman</option>
                    </select>
                </div>

                <!-- Kecamatan -->
                <div>
                    <label for="district" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Kecamatan <span class="text-red-500">*</span></label>
                    <select id="district" name="district" required class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E] bg-white">
                        <option value="">Pilih Kecamatan</option>
                        @foreach($districts as $d)
                            <option value="{{ $d }}" {{ old('district', $destination->district) === $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Lokasi Ringkas -->
                <div>
                    <label for="location" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Lokasi Ringkas <span class="text-red-500">*</span></label>
                    <input type="text" id="location" name="location" value="{{ old('location', $destination->location) }}" required class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>

                <!-- Alamat Lengkap -->
                <div class="sm:col-span-2">
                    <label for="address" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Alamat Lengkap</label>
                    <textarea id="address" name="address" rows="2" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">{{ old('address', $destination->address) }}</textarea>
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
                    <input type="text" id="open_hours" name="open_hours" value="{{ old('open_hours', $destination->open_hours) }}" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>

                <!-- Tarif Weekday -->
                <div>
                    <label for="weekday_price" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Tarif Hari Kerja (Rp)</label>
                    <input type="number" id="weekday_price" name="weekday_price" value="{{ old('weekday_price', $destination->weekday_price) }}" min="0" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>

                <!-- Tarif Weekend -->
                <div>
                    <label for="weekend_price" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Tarif Akhir Pekan (Rp)</label>
                    <input type="number" id="weekend_price" name="weekend_price" value="{{ old('weekend_price', $destination->weekend_price) }}" min="0" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>

                <!-- Telepon / WhatsApp -->
                <div>
                    <label for="phone" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Nomor Telepon / WA</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $destination->phone) }}" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>

                <!-- Rating -->
                <div>
                    <label for="rating" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Rating (0 - 5.0)</label>
                    <input type="number" step="0.1" min="0" max="5" id="rating" name="rating" value="{{ old('rating', $destination->rating) }}" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>

                <!-- Jumlah Ulasan -->
                <div>
                    <label for="review_count" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Jumlah Ulasan</label>
                    <input type="number" min="0" id="review_count" name="review_count" value="{{ old('review_count', $destination->review_count) }}" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>
            </div>

            <!-- Tags -->
            <div>
                <label for="tags" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Label / Tag (Pisahkan dengan tanda koma)</label>
                <input type="text" id="tags" name="tags" value="{{ old('tags', $tagsString) }}" placeholder="Alam, Sunset, Kuliner" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
            </div>
        </div>

        <!-- Deskripsi -->
        <div class="bg-white p-6 rounded-xl border border-[#D8E1EA] shadow-sm space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-[#123B6D] border-b border-gray-100 pb-2">
                3. Deskripsi Destinasi
            </h3>

            <div>
                <label for="description" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Deskripsi Singkat (Ringkasan kartu)</label>
                <textarea id="description" name="description" rows="2" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">{{ old('description', $destination->description) }}</textarea>
            </div>

            <div>
                <label for="long_description" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Deskripsi Lengkap (Halaman detail)</label>
                <textarea id="long_description" name="long_description" rows="5" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">{{ old('long_description', $destination->long_description) }}</textarea>
            </div>
        </div>

        <!-- Foto & Gambar -->
        <div class="bg-white p-6 rounded-xl border border-[#D8E1EA] shadow-sm space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-[#123B6D] border-b border-gray-100 pb-2">
                4. Foto & Media
            </h3>

            @if($destination->image)
                <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <img src="{{ $destination->image }}" alt="{{ $destination->name }}" class="w-20 h-20 rounded-lg object-cover border border-gray-300">
                    <div>
                        <p class="text-xs font-bold text-gray-800">Foto Saat Ini</p>
                        <p class="text-[11px] text-gray-500 break-all max-w-md">{{ $destination->getRawOriginal('image') }}</p>
                        <p class="text-[10px] text-gray-400 mt-1">Unggah berkas baru atau masukkan URL baru di bawah jika ingin mengganti foto ini.</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="image_file" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Ganti dengan Upload Berkas Baru</label>
                    <input type="file" id="image_file" name="image_file" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#EEF3F8] file:text-[#0E7C9E] hover:file:bg-[#dce7f2]">
                    <p class="text-[11px] text-gray-400 mt-1">Format: JPG, PNG, WEBP. Maks 4MB.</p>
                </div>

                <div>
                    <label for="image_url" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Atau Masukkan Tautan URL Baru</label>
                    <input type="url" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/foto-baru.jpg" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>
            </div>
        </div>

        <!-- Koordinat GIS & Peta -->
        <div class="bg-white p-6 rounded-xl border border-[#D8E1EA] shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                <h3 class="text-sm font-bold uppercase tracking-wider text-[#123B6D]">
                    5. Titik Koordinat Peta (GIS)
                </h3>
                <span class="text-[11px] text-gray-500">Klik atau geser pin pada peta untuk mengubah koordinat</span>
            </div>

            <div id="map-picker"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="latitude" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Latitude</label>
                    <input type="number" step="any" id="latitude" name="latitude" value="{{ old('latitude', $destination->latitude ?? -5.3582) }}" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>

                <div>
                    <label for="longitude" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Longitude</label>
                    <input type="number" step="any" id="longitude" name="longitude" value="{{ old('longitude', $destination->longitude ?? 104.9754) }}" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.destinations.index') }}" class="px-5 py-2.5 rounded-lg border border-[#D8E1EA] text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#0E7C9E] hover:bg-[#0b627d] text-white text-sm font-semibold shadow-md transition-colors">
                Perbarui Destinasi
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

        const map = L.map('map-picker').setView([defaultLat, defaultLng], 13);
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
