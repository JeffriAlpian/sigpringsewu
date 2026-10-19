@extends('layouts.admin')

@section('title', 'Kelola Berita & Artikel')
@section('header_title', 'Berita & Artikel Publikasi')

@section('content')
<div class="space-y-6">
    <!-- Action and Filter Bar -->
    <div class="bg-white p-5 rounded-xl border border-[#D8E1EA] shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-[#12181F]">Daftar Berita & Artikel Terbit</h2>
                <p class="text-xs text-[#4A5A6B]">Kelola artikel informasi seputar kegiatan pariwisata dan perkembangan UMKM Pringsewu</p>
            </div>
            <a href="{{ route('admin.berita.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold bg-[#0E7C9E] text-white hover:bg-[#0b627d] shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tulis Berita Baru
            </a>
        </div>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('admin.berita.index') }}" class="mt-4 pt-4 border-t border-gray-100 flex items-center gap-3">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul berita atau isi konten..." class="w-full pl-9 pr-4 py-2 text-xs rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <button type="submit" class="px-4 py-2 bg-[#123B6D] text-white rounded-lg text-xs font-semibold hover:bg-[#0e2f57] transition-colors">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.berita.index') }}" class="p-2 text-gray-500 hover:text-red-600 rounded-lg border border-gray-200 hover:bg-gray-50" title="Reset pencarian">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl border border-[#D8E1EA] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-600 uppercase font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3.5">Berita & Thumbnail</th>
                        <th class="px-4 py-3.5">Slug URL</th>
                        <th class="px-4 py-3.5">Tanggal Publikasi</th>
                        <th class="px-4 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($beritas as $item)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <!-- Thumbnail & Title -->
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    @if($item->image)
                                        <img src="{{ $item->image }}" alt="{{ $item->title }}" class="w-14 h-10 rounded-lg object-cover flex-shrink-0 border border-gray-200">
                                    @else
                                        <div class="w-14 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-[10px] text-gray-400 font-semibold flex-shrink-0">
                                            No Img
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-bold text-[#12181F] max-w-md line-clamp-1">{{ $item->title }}</p>
                                        <p class="text-[11px] text-[#4A5A6B] line-clamp-1">{{ Str::limit(strip_tags($item->content), 80) }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Slug -->
                            <td class="px-4 py-3.5 font-mono text-[11px] text-gray-500 max-w-[180px] truncate" title="{{ $item->slug }}">
                                /berita/{{ $item->slug }}
                            </td>

                            <!-- Published At -->
                            <td class="px-4 py-3.5 text-gray-700 whitespace-nowrap">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>{{ $item->published_at ? $item->published_at->format('d M Y, H:i') : $item->created_at->format('d M Y') }}</span>
                                </div>
                            </td>

                            <!-- Aksi -->
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('berita.detail', $item->slug) }}" target="_blank" class="p-1.5 text-gray-500 hover:text-[#0E7C9E] rounded hover:bg-gray-100 transition-colors" title="Lihat di Web">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.berita.edit', $item) }}" class="p-1.5 text-blue-600 hover:text-blue-800 rounded hover:bg-blue-50 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.berita.destroy', $item) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita {{ addslashes($item->title) }}?');">
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
                            <td colspan="4" class="px-4 py-12 text-center">
                                <div class="max-w-xs mx-auto">
                                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                    <p class="text-sm font-semibold text-gray-600">Belum ada berita ditemukan</p>
                                    <p class="text-xs text-gray-400 mt-1">Gunakan tombol "Tulis Berita Baru" untuk menambahkan artikel pertama Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($beritas->hasPages())
            <div class="p-4 bg-gray-50 border-t border-gray-100">
                {{ $beritas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
