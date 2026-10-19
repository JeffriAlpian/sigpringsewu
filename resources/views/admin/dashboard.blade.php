@extends('layouts.admin')

@section('title', 'Dasbor Pengelolaan Konten')
@section('header_title', 'Ringkasan & Pengelolaan Konten')

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#123B6D] via-[#0f4b82] to-[#0E7C9E] p-6 sm:p-8 text-white shadow-md">
        <div class="relative z-10 max-w-3xl">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-sm mb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Panel Administrator Aktif
            </span>
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight mb-2">
                Selamat Datang, {{ auth()->user()->name }}!
            </h2>
            <p class="text-sm sm:text-base text-white/80 leading-relaxed">
                Ini adalah pusat kendali untuk mengelola seluruh konten sistem informasi geografis Pringsewu. Anda dapat menambah, menyunting, dan memperbarui data destinasi wisata, UMKM, berita berkala, serta mengelola akses pengguna.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('admin.destinations.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold bg-white text-[#123B6D] hover:bg-gray-100 shadow transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Destinasi Baru
                </a>
                <a href="{{ route('admin.berita.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold bg-white/10 hover:bg-white/20 text-white border border-white/20 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Tulis Berita Baru
                </a>
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold bg-white/10 hover:bg-white/20 text-white border border-white/20 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    Tambah Pengguna
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-6">
        <!-- Total Destinasi -->
        <div class="bg-white p-5 rounded-xl border border-[#D8E1EA] shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#123B6D] flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-[#4A5A6B] uppercase tracking-wider">Total Destinasi</p>
                <h3 class="text-2xl font-bold text-[#12181F]">{{ $totalDestinations }}</h3>
                <span class="text-[11px] text-[#4A5A6B]">Wisata & UMKM</span>
            </div>
        </div>

        <!-- Wisata -->
        <div class="bg-white p-5 rounded-xl border border-[#D8E1EA] shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-cyan-50 text-[#0E7C9E] flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-[#4A5A6B] uppercase tracking-wider">Destinasi Wisata</p>
                <h3 class="text-2xl font-bold text-[#12181F]">{{ $totalWisata }}</h3>
                <span class="text-[11px] text-emerald-600 font-medium">Terverifikasi</span>
            </div>
        </div>

        <!-- UMKM -->
        <div class="bg-white p-5 rounded-xl border border-[#D8E1EA] shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-[#4A5A6B] uppercase tracking-wider">Sentra UMKM</p>
                <h3 class="text-2xl font-bold text-[#12181F]">{{ $totalUmkm }}</h3>
                <span class="text-[11px] text-amber-600 font-medium">Lokal Pringsewu</span>
            </div>
        </div>

        <!-- Berita -->
        <div class="bg-white p-5 rounded-xl border border-[#D8E1EA] shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-[#4A5A6B] uppercase tracking-wider">Artikel & Berita</p>
                <h3 class="text-2xl font-bold text-[#12181F]">{{ $totalBerita }}</h3>
                <span class="text-[11px] text-purple-600 font-medium">Terbit di Web</span>
            </div>
        </div>

        <!-- Pengguna -->
        <div class="bg-white p-5 rounded-xl border border-[#D8E1EA] shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-[#4A5A6B] uppercase tracking-wider">Total Akun</p>
                <h3 class="text-2xl font-bold text-[#12181F]">{{ $totalUsers }}</h3>
                <span class="text-[11px] text-[#4A5A6B]">{{ $totalAdmins }} Admin · {{ $totalRegularUsers }} User</span>
            </div>
        </div>
    </div>

    <!-- District Breakdown & Quick Links -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Persebaran Lokasi per Kecamatan -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-[#D8E1EA] shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-[#12181F]">Persebaran Destinasi per Kecamatan</h3>
                    <p class="text-xs text-[#4A5A6B]">Distribusi titik lokasi Wisata dan UMKM di wilayah Kabupaten Pringsewu</p>
                </div>
                <a href="{{ route('admin.destinations.index') }}" class="text-xs font-semibold text-[#0E7C9E] hover:underline">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @forelse($districtStats as $stat)
                    <div class="p-3 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500">Kecamatan</p>
                            <p class="text-sm font-bold text-[#12181F]">{{ $stat->district }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-bold rounded-md bg-[#EEF3F8] text-[#0E7C9E]">
                            {{ $stat->total }} Lokasi
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 col-span-3">Belum ada data kecamatan tersedia.</p>
                @endforelse
            </div>
        </div>

        <!-- Navigasi Aksi Cepat -->
        <div class="bg-white rounded-xl border border-[#D8E1EA] shadow-sm p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-[#12181F] mb-1">Aksi Cepat Pengelolaan</h3>
                <p class="text-xs text-[#4A5A6B] mb-4">Akses cepat ke menu administrasi yang sering digunakan.</p>

                <div class="space-y-2.5">
                    <a href="{{ route('admin.destinations.index', ['category' => 'Wisata']) }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-[#EEF3F8] border border-gray-200 transition-colors group">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-md bg-blue-100 text-[#123B6D] flex items-center justify-center text-xs font-bold">W</span>
                            <div>
                                <p class="text-xs font-semibold text-gray-900 group-hover:text-[#0E7C9E]">Kelola Wisata</p>
                                <p class="text-[11px] text-gray-500">Filter destinasi wisata</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-[#0E7C9E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <a href="{{ route('admin.destinations.index', ['category' => 'UMKM']) }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-[#EEF3F8] border border-gray-200 transition-colors group">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-md bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-bold">U</span>
                            <div>
                                <p class="text-xs font-semibold text-gray-900 group-hover:text-[#0E7C9E]">Kelola UMKM</p>
                                <p class="text-[11px] text-gray-500">Filter sentra UMKM</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-[#0E7C9E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <a href="{{ route('admin.berita.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-[#EEF3F8] border border-gray-200 transition-colors group">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-md bg-purple-100 text-purple-700 flex items-center justify-center text-xs font-bold">B</span>
                            <div>
                                <p class="text-xs font-semibold text-gray-900 group-hover:text-[#0E7C9E]">Kelola Berita</p>
                                <p class="text-[11px] text-gray-500">Publikasi dan artikel</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-[#0E7C9E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-[#EEF3F8] border border-gray-200 transition-colors group">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-md bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">P</span>
                            <div>
                                <p class="text-xs font-semibold text-gray-900 group-hover:text-[#0E7C9E]">Kelola Pengguna</p>
                                <p class="text-[11px] text-gray-500">Akses & role akun</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-[#0E7C9E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                <span class="text-xs text-gray-500">Pratinjau Peta Interaktif</span>
                <a href="{{ route('peta') }}" target="_blank" class="text-xs font-bold text-[#123B6D] hover:underline inline-flex items-center gap-1">
                    Buka Peta GIS &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Tables: Latest Destinations & Latest Berita -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 5 Destinasi Terbaru -->
        <div class="bg-white rounded-xl border border-[#D8E1EA] shadow-sm overflow-hidden">
            <div class="p-5 border-b border-[#D8E1EA] flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-[#12181F]">Destinasi Terbaru</h3>
                    <p class="text-xs text-[#4A5A6B]">Data destinasi wisata & UMKM yang baru ditambahkan</p>
                </div>
                <a href="{{ route('admin.destinations.create') }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-[#0E7C9E] text-white hover:bg-[#0b627d] transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 text-gray-600 uppercase font-semibold border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3">Nama Destinasi</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Kecamatan</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentDestinations as $dest)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 font-semibold text-[#12181F]">
                                    <div class="flex items-center gap-2">
                                        @if($dest->image)
                                            <img src="{{ $dest->image }}" alt="" class="w-8 h-8 rounded object-cover flex-shrink-0">
                                        @else
                                            <div class="w-8 h-8 rounded bg-gray-200 flex items-center justify-center text-[10px] text-gray-500 flex-shrink-0">N/A</div>
                                        @endif
                                        <span class="truncate max-w-[150px]">{{ $dest->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold {{ $dest->category === 'Wisata' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $dest->category }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $dest->district }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold {{ $dest->status === 'Buka' ? 'bg-emerald-100 text-emerald-800' : ($dest->status === 'Tutup' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ $dest->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.destinations.edit', $dest) }}" class="text-[#0E7C9E] hover:underline font-semibold">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada destinasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 bg-gray-50 border-t border-gray-100 text-center">
                <a href="{{ route('admin.destinations.index') }}" class="text-xs font-semibold text-[#123B6D] hover:underline">
                    Lihat Semua Destinasi ({{ $totalDestinations }}) &rarr;
                </a>
            </div>
        </div>

        <!-- 5 Berita Terbaru -->
        <div class="bg-white rounded-xl border border-[#D8E1EA] shadow-sm overflow-hidden">
            <div class="p-5 border-b border-[#D8E1EA] flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-[#12181F]">Berita & Artikel Terbaru</h3>
                    <p class="text-xs text-[#4A5A6B]">Publikasi artikel informasi terkini</p>
                </div>
                <a href="{{ route('admin.berita.create') }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-[#0E7C9E] text-white hover:bg-[#0b627d] transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tulis
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 text-gray-600 uppercase font-semibold border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3">Judul Berita</th>
                            <th class="px-4 py-3">Tanggal Terbit</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentBerita as $news)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 font-semibold text-[#12181F]">
                                    <div class="flex items-center gap-2">
                                        @if($news->image)
                                            <img src="{{ $news->image }}" alt="" class="w-8 h-8 rounded object-cover flex-shrink-0">
                                        @else
                                            <div class="w-8 h-8 rounded bg-gray-200 flex items-center justify-center text-[10px] text-gray-500 flex-shrink-0">N/A</div>
                                        @endif
                                        <span class="truncate max-w-[220px]">{{ $news->title }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $news->published_at ? $news->published_at->format('d M Y') : $news->created_at->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.berita.edit', $news) }}" class="text-[#0E7C9E] hover:underline font-semibold">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-gray-400">Belum ada berita.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 bg-gray-50 border-t border-gray-100 text-center">
                <a href="{{ route('admin.berita.index') }}" class="text-xs font-semibold text-[#123B6D] hover:underline">
                    Lihat Semua Berita ({{ $totalBerita }}) &rarr;
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
