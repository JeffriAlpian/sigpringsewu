@extends('layouts.admin')

@section('title', 'Kelola Destinasi Wisata & UMKM')
@section('header_title', 'Destinasi Wisata & UMKM')

@section('content')
<div class="space-y-6">
    <!-- Action and Filter Bar -->
    <div class="bg-white p-5 rounded-xl border border-[#D8E1EA] shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <div>
                <h2 class="text-lg font-bold text-[#12181F]">Daftar Destinasi & Sentra Usaha</h2>
                <p class="text-xs text-[#4A5A6B]">Kelola data destinasi wisata dan UMKM yang ditampilkan pada peta dan katalog</p>
            </div>
            <a href="{{ route('admin.destinations.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold bg-[#0E7C9E] text-white hover:bg-[#0b627d] shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Destinasi Baru
            </a>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.destinations.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 pt-4 border-t border-gray-100">
            <!-- Search Input -->
            <div class="lg:col-span-2">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau lokasi..." class="w-full pl-9 pr-4 py-2 text-xs rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Category Filter -->
            <div>
                <select name="category" class="w-full px-3 py-2 text-xs rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E] bg-white">
                    <option value="">Semua Kategori</option>
                    <option value="Wisata" {{ request('category') === 'Wisata' ? 'selected' : '' }}>Wisata</option>
                    <option value="UMKM" {{ request('category') === 'UMKM' ? 'selected' : '' }}>UMKM</option>
                </select>
            </div>

            <!-- District Filter -->
            <div>
                <select name="district" class="w-full px-3 py-2 text-xs rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E] bg-white">
                    <option value="">Semua Kecamatan</option>
                    @foreach($districts as $d)
                        <option value="{{ $d }}" {{ request('district') === $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter & Submit -->
            <div class="flex items-center gap-2">
                <select name="status" class="w-full px-3 py-2 text-xs rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E] bg-white">
                    <option value="">Semua Status</option>
                    <option value="Buka" {{ request('status') === 'Buka' ? 'selected' : '' }}>Buka</option>
                    <option value="Tutup" {{ request('status') === 'Tutup' ? 'selected' : '' }}>Tutup</option>
                    <option value="Musiman" {{ request('status') === 'Musiman' ? 'selected' : '' }}>Musiman</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-[#123B6D] text-white rounded-lg text-xs font-semibold hover:bg-[#0e2f57] transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'category', 'district', 'status']))
                    <a href="{{ route('admin.destinations.index') }}" class="p-2 text-gray-500 hover:text-red-600 rounded-lg border border-gray-200 hover:bg-gray-50" title="Reset filter">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl border border-[#D8E1EA] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-600 uppercase font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3.5">Destinasi</th>
                        <th class="px-4 py-3.5">Kategori</th>
                        <th class="px-4 py-3.5">Kecamatan & Alamat</th>
                        <th class="px-4 py-3.5">Rating / Ulasan</th>
                        <th class="px-4 py-3.5">Tarif</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($destinations as $item)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <!-- Destinasi Info -->
                            <td class="px-4 py-3.5 font-medium">
                                <div class="flex items-center gap-3">
                                    @if($item->image)
                                        <img src="{{ $item->image }}" alt="{{ $item->name }}" class="w-12 h-12 rounded-lg object-cover flex-shrink-0 border border-gray-200">
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-[10px] text-gray-400 font-semibold flex-shrink-0">
                                            No Img
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-bold text-[#12181F]">{{ $item->name }}</p>
                                        <p class="text-[11px] text-[#4A5A6B]">{{ $item->location }}</p>
                                        @if(!empty($item->tags) && is_array($item->tags))
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                @foreach(array_slice($item->tags, 0, 2) as $tag)
                                                    <span class="px-1.5 py-0.5 rounded text-[9px] bg-gray-100 text-gray-600 font-medium">{{ $tag }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Kategori -->
                            <td class="px-4 py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold {{ $item->category === 'Wisata' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $item->category }}
                                </span>
                            </td>

                            <!-- Kecamatan & Alamat -->
                            <td class="px-4 py-3.5">
                                <p class="font-semibold text-gray-900">{{ $item->district }}</p>
                                <p class="text-[11px] text-gray-500 max-w-[200px] truncate" title="{{ $item->address }}">{{ $item->address ?: '-' }}</p>
                            </td>

                            <!-- Rating -->
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-yellow-500 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span class="font-bold text-gray-900">{{ number_format($item->rating, 1) }}</span>
                                    <span class="text-[10px] text-gray-400">({{ $item->review_count }})</span>
                                </div>
                            </td>

                            <!-- Tarif -->
                            <td class="px-4 py-3.5 text-gray-700">
                                @if($item->weekday_price || $item->weekend_price)
                                    <div>
                                        <p class="text-[11px]">Wd: Rp {{ number_format($item->weekday_price, 0, ',', '.') }}</p>
                                        <p class="text-[11px]">We: Rp {{ number_format($item->weekend_price, 0, ',', '.') }}</p>
                                    </div>
                                @else
                                    <span class="text-[11px] text-emerald-600 font-medium">Gratis / -</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-3.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold {{ $item->status === 'Buka' ? 'bg-emerald-100 text-emerald-800' : ($item->status === 'Tutup' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ $item->status }}
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('detail', $item->id) }}" target="_blank" class="p-1.5 text-gray-500 hover:text-[#0E7C9E] rounded hover:bg-gray-100 transition-colors" title="Lihat di Web">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.destinations.edit', $item) }}" class="p-1.5 text-blue-600 hover:text-blue-800 rounded hover:bg-blue-50 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.destinations.destroy', $item) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus destinasi {{ addslashes($item->name) }}? Tindakan ini tidak dapat dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-600 hover:text-red-800 rounded hover:bg-red-50 transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <div class="max-w-xs mx-auto">
                                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p class="text-sm font-semibold text-gray-600">Tidak ada destinasi ditemukan</p>
                                    <p class="text-xs text-gray-400 mt-1">Coba sesuaikan kata kunci pencarian atau bersihkan filter.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($destinations->hasPages())
            <div class="p-4 bg-gray-50 border-t border-gray-100">
                {{ $destinations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
