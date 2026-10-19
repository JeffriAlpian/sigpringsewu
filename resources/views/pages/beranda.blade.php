@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col" x-data="{ activeCat: 'Semua', searchQuery: '' }">
    <!-- Hero -->
    <section class="relative overflow-hidden" style="min-height: 480px">
        <img src="https://images.unsplash.com/photo-1558005530-a7958896ec60?w=1600&h=700&fit=crop&auto=format" alt="Pemandangan alam Pringsewu" class="absolute inset-0 w-full h-full object-cover" />
        <div class="absolute inset-0" style="background: rgba(18, 24, 31, 0.48)"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
            <div class="max-w-2xl">
                <p class="text-[12px] font-semibold uppercase tracking-widest mb-3" style="color: #EEF3F8; opacity: 0.8">
                    Sistem Informasi Geografis · Kabupaten Pringsewu
                </p>
                <h1 class="text-[28px] sm:text-[36px] font-bold leading-tight mb-4" style="color: #ffffff">
                    Jelajahi Destinasi Wisata & UMKM di Kabupaten Pringsewu
                </h1>
                <p class="text-[16px] leading-relaxed mb-8" style="color: #D8E1EA">
                    Data lokasi wisata dan usaha mikro yang dikurasi bersama Dinas Pariwisata Pringsewu.
                </p>
                                <!-- Search bar -->
                <div class="flex gap-2 mt-6">
                    <input x-model="searchQuery" @input="document.getElementById('grid-destinasi').scrollIntoView({behavior: 'smooth', block: 'start'})" type="search" placeholder="Cari destinasi, kategori, atau lokasi..." class="flex-1 px-4 py-3.5 text-[15px] bg-white outline-none focus:border-[#0E7C9E] focus:border-2 shadow-lg transition-all" style="border: 1px solid transparent; border-radius: 6px; color: #12181F;" />
                    <button @click="document.getElementById('grid-destinasi').scrollIntoView({behavior: 'smooth', block: 'start'})" class="px-6 py-3.5 text-[15px] font-semibold whitespace-nowrap shadow-lg hover:bg-[#0b627d] transition-colors" style="background: #0E7C9E; color: #ffffff; border-radius: 6px;">
                        Cari
                    </button>
                </div>
                <!-- Quick Pills -->
                <div class="flex flex-wrap items-center gap-2 mt-4">
                    <span class="text-[13px] text-white/80 mr-1">Pencarian Populer:</span>
                    <button @click="searchQuery = ''; activeCat = 'Wisata'; document.getElementById('grid-destinasi').scrollIntoView({behavior: 'smooth', block: 'start'})" class="px-3 py-1.5 text-[12px] font-medium bg-white/20 hover:bg-white/30 text-white rounded-full backdrop-blur-sm transition-colors">Wisata Alam</button>
                    <button @click="searchQuery = ''; activeCat = 'UMKM'; document.getElementById('grid-destinasi').scrollIntoView({behavior: 'smooth', block: 'start'})" class="px-3 py-1.5 text-[12px] font-medium bg-white/20 hover:bg-white/30 text-white rounded-full backdrop-blur-sm transition-colors">Oleh-Oleh</button>
                    <button @click="searchQuery = 'sukoharjo'; activeCat = 'Semua'; document.getElementById('grid-destinasi').scrollIntoView({behavior: 'smooth', block: 'start'})" class="px-3 py-1.5 text-[12px] font-medium bg-white/20 hover:bg-white/30 text-white rounded-full backdrop-blur-sm transition-colors">Area Sukoharjo</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats bar -->
    <div style="background: #EEF3F8; border-bottom: 1px solid #D8E1EA">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex">
                <div class="flex-1 py-3 px-4 text-center" style="border-right: 1px solid #D8E1EA">
                    <div class="text-[20px] font-bold" style="color: #123B6D">{{ \App\Models\Destination::where('category', 'Wisata')->count() }}</div>
                    <div class="text-[12px]" style="color: #4A5A6B">Destinasi Wisata</div>
                </div>
                <div class="flex-1 py-3 px-4 text-center" style="border-right: 1px solid #D8E1EA">
                    <div class="text-[20px] font-bold" style="color: #123B6D">{{ \App\Models\Destination::where('category', 'UMKM')->count() }}</div>
                    <div class="text-[12px]" style="color: #4A5A6B">UMKM Terdaftar</div>
                </div>
                <div class="flex-1 py-3 px-4 text-center" style="border-right: 1px solid #D8E1EA">
                    <div class="text-[20px] font-bold" style="color: #123B6D">9</div>
                    <div class="text-[12px]" style="color: #4A5A6B">Kecamatan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <main id="grid-destinasi" class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10" style="scroll-margin-top: 80px;">
        <!-- Section header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
            <div>
                <h2 class="text-[20px] font-semibold" style="color: #12181F">Destinasi Populer</h2>
                <p class="text-[14px] mt-1" style="color: #4A5A6B">Terdapat {{ \App\Models\Destination::count() }} lokasi terdaftar</p>
            </div>
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
        </div>

        <!-- Card grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($destinations as $dest)
                <div x-show="(activeCat === 'Semua' || activeCat === '{{ $dest->category }}') && ('{{ strtolower($dest->name) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($dest->location) }}'.includes(searchQuery.toLowerCase()))" x-cloak>
                    <x-destination-card :destination="$dest" />
                </div>
            @endforeach
        </div>

        <!-- Empty state UMKM -->
        <div x-show="activeCat === 'UMKM'" x-cloak class="text-center py-12 px-4 bg-white border border-[#D8E1EA] rounded-lg my-6">
            <span class="text-3xl mb-2 inline-block">🏪</span>
            <h3 class="text-[16px] font-semibold text-[#12181F]">Data UMKM Sedang Disiapkan</h3>
            <p class="text-[13px] text-[#4A5A6B] mt-1">Saat ini data UMKM sedang dalam proses pendataan dan kurasi oleh tim dinas terkait.</p>
        </div>

        <!-- CTA section -->
        <div class="mt-12 p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4" style="background: #EEF3F8; border: 1px solid #D8E1EA; border-radius: 6px;">
            <div>
                <h3 class="text-[17px] font-semibold mb-1" style="color: #123B6D">Punya usaha atau destinasi wisata di Pringsewu?</h3>
                <p class="text-[14px]" style="color: #4A5A6B">Daftarkan lokasi Anda agar mudah ditemukan wisatawan dan pembeli.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="window.location.href='{{ route('login') }}'" class="px-5 py-2.5 text-[14px] font-semibold" style="background: #0E7C9E; color: #ffffff; border-radius: 6px;">
                    Daftar Sekarang
                </button>
                <button onclick="window.location.href='{{ route('peta') }}'" class="px-5 py-2.5 text-[14px] font-semibold" style="border: 1px solid #D8E1EA; color: #123B6D; border-radius: 6px; background: transparent;">
                    Lihat Peta
                </button>
            </div>
        </div>
    </main>
</div>
@endsection

