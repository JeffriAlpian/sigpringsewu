@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<style>
    #map { height: 100%; width: 100%; z-index: 0; }
</style>
@endpush

@section('content')
<div class="flex h-[calc(100vh-56px)] overflow-hidden w-full relative" x-data="{ activeCat: 'Semua', showSidebar: false }" x-effect="if (window.filterMarkers) window.filterMarkers(activeCat)">
    
    <!-- Mobile Toggle Button -->
    <button @click="showSidebar = true" class="lg:hidden absolute bottom-8 left-1/2 -translate-x-1/2 z-[1001] px-5 py-3 shadow-lg flex items-center gap-2 font-semibold text-[14px]" style="background: #123B6D; color: white; border-radius: 30px;">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
        Daftar Lokasi
    </button>

    <!-- Sidebar -->
    <aside class="flex flex-col w-full sm:w-80 flex-shrink-0 overflow-y-auto bg-white absolute inset-y-0 left-0 z-[2000] lg:relative lg:z-10 transition-transform duration-300 transform" 
           :class="showSidebar ? 'translate-x-0 shadow-2xl' : '-translate-x-full lg:translate-x-0'"
           style="border-right: 1px solid #D8E1EA;">
        <div class="p-4 flex items-center justify-between" style="border-bottom: 1px solid #D8E1EA">
            <h2 class="text-[16px] font-semibold" style="color: #12181F">Daftar Lokasi</h2>
            <button @click="showSidebar = false" class="lg:hidden p-1 text-gray-500 hover:text-gray-900 focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="px-4 pt-1" style="border-bottom: 1px solid #D8E1EA">
            <!-- Category tabs -->
            <div class="flex gap-0">
                <button @click="activeCat = 'Semua'" class="relative px-3 pb-2 pt-1 text-[13px] font-semibold" :class="activeCat === 'Semua' ? 'text-[#0E7C9E]' : 'text-[#4A5A6B]'">
                    Semua
                    <span x-show="activeCat === 'Semua'" class="absolute bottom-[-1px] left-0 right-0 h-0.5 bg-[#0E7C9E]" x-cloak></span>
                </button>
                <button @click="activeCat = 'Wisata'" class="relative px-3 pb-2 pt-1 text-[13px] font-semibold" :class="activeCat === 'Wisata' ? 'text-[#0E7C9E]' : 'text-[#4A5A6B]'">Wisata</button>
                <button @click="activeCat = 'UMKM'" class="relative px-3 pb-2 pt-1 text-[13px] font-semibold" :class="activeCat === 'UMKM' ? 'text-[#0E7C9E]' : 'text-[#4A5A6B]'">UMKM</button>
            </div>
        </div>

        <!-- Location list -->
        <div class="flex-1 overflow-y-auto">
            @foreach($destinations as $dest)
            <button x-show="activeCat === 'Semua' || activeCat === '{{ $dest->category }}'" 
                    class="w-full text-left p-4 flex gap-3 hover:bg-[#EEF3F8] transition-colors" 
                    style="border-bottom: 1px solid #D8E1EA; border-left: 3px solid transparent;" 
                    onclick="focusMap({{ $dest->latitude }}, {{ $dest->longitude }}, {{ $dest->id }}); showSidebar = false">
                <img src="{{ $dest->image }}" alt="{{ $dest->name }}" class="w-12 h-12 object-cover flex-shrink-0" style="border-radius: 4px" />
                <div class="flex-1 min-w-0">
                    <p class="text-[14px] font-semibold truncate" style="color: #12181F">{{ $dest->name }}</p>
                    <p class="text-[12px] mt-0.5" style="color: #4A5A6B">
                        {{ $dest->category === 'Wisata' ? '⛰' : '🏪' }} 
                        {{ $dest->category }} · {{ $dest->location }}
                    </p>
                    <p class="text-[12px] mt-0.5" style="color: #0E7C9E">★ {{ number_format($dest->rating, 1) }}</p>
                </div>
            </button>
            @endforeach

            <!-- Empty state UMKM in map sidebar -->
            <div x-show="activeCat === 'UMKM'" x-cloak class="p-8 text-center text-[#4A5A6B]">
                <p class="text-3xl mb-2">🏪</p>
                <p class="text-[14px] font-semibold text-[#12181F]">Data UMKM Kosong</p>
                <p class="text-[12px] text-[#4A5A6B] mt-1">Data lokasi UMKM sedang dalam proses pembaharuan.</p>
            </div>
        </div>
    </aside>

    <!-- Map area -->
    <div class="flex-1 relative z-0">
        <div id="map"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
    const map = L.map('map').setView([-5.3569, 104.9758], 12);
    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; <a href="https://www.esri.com/">Esri</a>'
    }).addTo(map);

    // Fix leaflet default icons for CDN deployment
    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
    });

    // Memuat garis batas wilayah Pringsewu dari GeoJSON
    fetch('{{ asset('pringsewu.geojson') }}')
        .then(response => response.json())
        .then(data => {
            L.geoJSON(data, {
                style: {
                    color: '#facc15', // Warna kuning kontras
                    weight: 4,
                    opacity: 1,
                    fill: false
                }
            }).addTo(map);
        })
        .catch(error => console.error('Gagal memuat batas wilayah Pringsewu:', error));

    const destinations = @json($destinations);
    const markers = {};
    
    destinations.forEach(dest => {
        if (!dest.latitude || !dest.longitude) return;
        
        const marker = L.marker([dest.latitude, dest.longitude]).addTo(map);
        marker.bindPopup(`
            <div style="width: 200px;">
                <img src="${dest.image}" style="width: 100%; height: 96px; object-fit: cover; border-radius: 6px 6px 0 0; margin-bottom: 8px;" />
                <span style="font-size: 10px; font-weight: 600; text-transform: uppercase; color: #0E7C9E;">${dest.category}</span>
                <h3 style="font-size: 14px; font-weight: bold; color: #1f2937; margin: 4px 0;">${dest.name}</h3>
                <p style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">${dest.location}</p>
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-size: 12px; font-weight: 600; color: #0E7C9E;">★ ${dest.rating} (${dest.review_count})</span>
                </div>
                <a href="{{ url('/detail') }}/${dest.id}" style="display: block; width: 100%; padding: 6px 0; background: #0E7C9E; color: white; text-align: center; border-radius: 4px; font-weight: 600; text-decoration: none; font-size: 12px;">
                    Lihat Detail →
                </a>
            </div>
        `);
        markers[dest.id] = marker;
    });

    window.filterMarkers = function(cat) {
        if (!map || !markers) return;
        destinations.forEach(dest => {
            const marker = markers[dest.id];
            if (!marker) return;
            if (cat === 'Semua' || dest.category === cat) {
                if (!map.hasLayer(marker)) {
                    marker.addTo(map);
                }
            } else {
                if (map.hasLayer(marker)) {
                    map.removeLayer(marker);
                }
            }
        });
    };

    window.focusMap = function(lat, lng, id) {
        map.flyTo([lat, lng], 16, { duration: 1.5 });
        if (id && markers[id]) {
            setTimeout(() => {
                markers[id].openPopup();
            }, 500); // Tunda sedikit agar animasi flyTo berjalan halus
        }
    }
</script>
@endpush
