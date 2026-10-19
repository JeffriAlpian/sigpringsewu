@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col w-full" x-data="{ activeCat: 'Semua', searchQuery: '', sortBy: 'rating' }">
    <!-- Page header -->
    <div class="py-8" style="background: #EEF3F8; border-bottom: 1px solid #D8E1EA">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-[28px] font-bold mb-1" style="color: #12181F">
                Daftar Produk & Destinasi
            </h1>
            <p class="text-[15px]" style="color: #4A5A6B">
                {{ count($destinations) }} lokasi terdaftar di Kabupaten Pringsewu
            </p>
        </div>
    </div>

    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filter toolbar -->
        <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between mb-6 pb-4" style="border-bottom: 1px solid #D8E1EA">
            <!-- Category tabs -->
            <div class="flex gap-0" style="border-bottom: 1px solid #D8E1EA">
                <button @click="activeCat = 'Semua'" class="relative px-4 pb-2 pt-1 text-[14px] font-semibold" :class="activeCat === 'Semua' ? 'text-[#0E7C9E]' : 'text-[#4A5A6B]'">
                    Semua
                    <span x-show="activeCat === 'Semua'" class="absolute bottom-[-1px] left-0 right-0 h-0.5 bg-[#0E7C9E]" x-cloak></span>
                </button>
                <button @click="activeCat = 'Wisata'" class="relative px-4 pb-2 pt-1 text-[14px] font-semibold" :class="activeCat === 'Wisata' ? 'text-[#0E7C9E]' : 'text-[#4A5A6B]'">
                    Wisata
                    <span x-show="activeCat === 'Wisata'" class="absolute bottom-[-1px] left-0 right-0 h-0.5 bg-[#0E7C9E]" x-cloak></span>
                </button>
                <button @click="activeCat = 'UMKM'" class="relative px-4 pb-2 pt-1 text-[14px] font-semibold" :class="activeCat === 'UMKM' ? 'text-[#0E7C9E]' : 'text-[#4A5A6B]'">
                    UMKM
                    <span x-show="activeCat === 'UMKM'" class="absolute bottom-[-1px] left-0 right-0 h-0.5 bg-[#0E7C9E]" x-cloak></span>
                </button>
            </div>

            <div class="flex items-center gap-3">
                <!-- Search -->
                <input type="search" x-model="searchQuery" placeholder="Cari nama atau lokasi…" class="px-3 py-2 text-[14px] bg-white outline-none focus:border-[#0E7C9E] focus:border-2" style="border: 1px solid #D8E1EA; border-radius: 4px; color: #12181F; max-width: 200px;" />

                <!-- Sort -->
                <div class="flex items-center gap-2">
                    <span class="text-[13px]" style="color: #4A5A6B">Urutkan:</span>
                    <select x-model="sortBy" class="px-3 py-2 text-[14px] bg-white outline-none" style="border: 1px solid #D8E1EA; border-radius: 4px; color: #12181F;">
                        <option value="rating">Rating Tertinggi</option>
                        <option value="ulasan">Ulasan Terbanyak</option>
                        <option value="nama">Nama A–Z</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Note: Real client-side filtering via Alpine requires passing all data to JS. 
             Since this is Blade, we'll display all records or hide them via simple Alpine logic. -->

        <!-- Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($destinations as $dest)
                <div x-show="(activeCat === 'Semua' || activeCat === '{{ $dest->category }}') && ('{{ strtolower($dest->name) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($dest->location) }}'.includes(searchQuery.toLowerCase()))">
                    <x-destination-card :destination="$dest" />
                </div>
            @endforeach
        </div>

        <!-- Empty state UMKM -->
        <div x-show="activeCat === 'UMKM'" x-cloak class="text-center py-16 px-4 bg-white border border-[#D8E1EA] rounded-lg my-6">
            <span class="text-4xl mb-3 inline-block">🏪</span>
            <h3 class="text-[17px] font-semibold text-[#12181F]">Data UMKM Sedang Disiapkan</h3>
            <p class="text-[14px] text-[#4A5A6B] mt-1.5 max-w-md mx-auto">Untuk saat ini data produk & UMKM Kabupaten Pringsewu sedang dikosongkan untuk pembaruan data resmi.</p>
        </div>
    </main>
</div>
@endsection
