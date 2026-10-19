@extends('layouts.app')

@section('content')
<div class="bg-white min-h-[calc(100vh-56px)] py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="mb-10 text-center">
            <h1 class="text-3xl sm:text-4xl font-bold text-[#12181F] mb-4">Berita & Artikel</h1>
            <p class="text-[15px] text-[#4A5A6B] max-w-2xl mx-auto">
                Dapatkan informasi terbaru seputar destinasi wisata, acara, dan perkembangan UMKM di Kabupaten Pringsewu.
            </p>
        </div>

        <!-- Berita List -->
        @if($beritas->count() > 0)
            <div class="flex flex-col gap-8">
                @foreach($beritas as $item)
                    <article class="flex flex-col sm:flex-row bg-white rounded-xl overflow-hidden border border-[#D8E1EA] hover:shadow-lg transition-shadow duration-300">
                        <a href="{{ route('berita.detail', $item->slug) }}" class="block sm:w-2/5 lg:w-1/3 flex-shrink-0">
                            @if($item->image)
                                <img src="{{ $item->image }}" alt="{{ $item->title }}" class="w-full h-full object-cover aspect-video sm:aspect-auto transform hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-[#EEF3F8] flex items-center justify-center min-h-[200px]">
                                    <svg class="w-12 h-12 text-[#4A5A6B] opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </a>
                        <div class="p-6 flex-1 flex flex-col justify-center">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-[#EEF3F8] text-[#0E7C9E]">
                                    Berita
                                </span>
                                <span class="text-[13px] text-[#4A5A6B]">
                                    {{ $item->published_at ? $item->published_at->format('d M Y') : $item->created_at->format('d M Y') }}
                                </span>
                            </div>
                            <h2 class="text-[20px] font-bold text-[#12181F] mb-3 leading-snug">
                                <a href="{{ route('berita.detail', $item->slug) }}" class="hover:text-[#0E7C9E] transition-colors">
                                    {{ $item->title }}
                                </a>
                            </h2>
                            <p class="text-[15px] text-[#4A5A6B] mb-5 line-clamp-3">
                                {{ Str::limit(strip_tags($item->content), 150) }}
                            </p>
                            <div class="mt-auto">
                                <a href="{{ route('berita.detail', $item->slug) }}" class="inline-flex items-center gap-2 text-[14px] font-semibold text-[#0E7C9E] hover:text-[#123B6D] transition-colors">
                                    Baca Selengkapnya
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                {{ $beritas->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-[#EEF3F8] rounded-xl border border-dashed border-[#D8E1EA]">
                <svg class="w-16 h-16 text-[#4A5A6B] mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5L18.5 7H16"></path></svg>
                <h3 class="text-[18px] font-semibold text-[#12181F] mb-2">Belum ada berita</h3>
                <p class="text-[14px] text-[#4A5A6B]">Saat ini belum ada data berita atau artikel yang dapat ditampilkan.</p>
            </div>
        @endif
        
    </div>
</div>
@endsection

