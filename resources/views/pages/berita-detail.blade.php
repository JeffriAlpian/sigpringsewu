@extends('layouts.app')

@section('content')
<div class="bg-[#F8FAFC] min-h-[calc(100vh-56px)] py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="flex text-[13px] text-[#4A5A6B] mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('beranda') }}" class="hover:text-[#0E7C9E] transition-colors">Beranda</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <a href="{{ route('berita') }}" class="hover:text-[#0E7C9E] transition-colors">Berita</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="text-[#12181F] font-medium truncate max-w-[200px] sm:max-w-xs">{{ $berita->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
            
            <!-- Main Content (Article) -->
            <article class="flex-1 bg-white rounded-2xl shadow-sm border border-[#D8E1EA] overflow-hidden">
                <!-- Cover Image -->
                @if($berita->image)
                    <div class="w-full aspect-[21/9] bg-[#EEF3F8]">
                        <img src="{{ $berita->image }}" alt="{{ $berita->title }}" class="w-full h-full object-cover">
                    </div>
                @endif
                
                <div class="p-6 sm:p-10">
                    <!-- Article Meta -->
                    <div class="flex flex-wrap items-center gap-4 mb-6 text-[14px] text-[#4A5A6B]">
                        <div class="flex items-center gap-1.5 bg-[#EEF3F8] px-3 py-1 rounded-full text-[#0E7C9E] font-semibold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5L18.5 7H16"></path></svg>
                            Berita
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $berita->published_at ? $berita->published_at->format('d F Y') : $berita->created_at->format('d F Y') }}
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Admin Pringsewu
                        </div>
                    </div>

                    <!-- Article Title -->
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-[#12181F] mb-8 leading-tight">
                        {{ $berita->title }}
                    </h1>

                    <!-- Article Body -->
                    <div class="prose prose-blue max-w-none text-[#2d3748] text-[16px] leading-relaxed">
                        {!! nl2br(e($berita->content)) !!}
                    </div>

                    <!-- Share Section (Placeholder) -->
                    <div class="mt-12 pt-6 border-t border-[#D8E1EA] flex items-center justify-between">
                        <span class="text-[14px] font-semibold text-[#12181F]">Bagikan artikel ini:</span>
                        <div class="flex gap-2">
                            <button class="w-9 h-9 rounded-full bg-[#EEF3F8] text-[#123B6D] flex items-center justify-center hover:bg-[#0E7C9E] hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                            </button>
                            <button class="w-9 h-9 rounded-full bg-[#EEF3F8] text-[#123B6D] flex items-center justify-center hover:bg-[#0E7C9E] hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-3 7h-1.924c-.615 0-1.076.252-1.076.889v1.111h3l-.238 3h-2.762v8h-3v-8h-2v-3h2v-1.923c0-2.022 1.064-3.077 3.461-3.077h2.539v3z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Aside (Sidebar) -->
            <aside class="w-full lg:w-[340px] flex-shrink-0">
                <div class="bg-white rounded-2xl shadow-sm border border-[#D8E1EA] p-6 sticky top-24">
                    <h3 class="text-[16px] font-bold text-[#12181F] mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#0E7C9E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5L18.5 7H16"></path></svg>
                        Berita Terbaru
                    </h3>
                    
                    <div class="flex flex-col gap-5">
                        @forelse($recentBeritas as $recent)
                            <a href="{{ route('berita.detail', $recent->slug) }}" class="group flex gap-4 items-start">
                                @if($recent->image)
                                    <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 bg-[#EEF3F8]">
                                        <img src="{{ $recent->image }}" alt="{{ $recent->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-[14px] font-bold text-[#12181F] leading-snug group-hover:text-[#0E7C9E] transition-colors line-clamp-2 mb-1">
                                        {{ $recent->title }}
                                    </h4>
                                    <p class="text-[12px] text-[#4A5A6B]">
                                        {{ $recent->published_at ? $recent->published_at->format('d M Y') : $recent->created_at->format('d M Y') }}
                                    </p>
                                </div>
                            </a>
                        @empty
                            <p class="text-[13px] text-[#4A5A6B] italic">Belum ada berita lainnya.</p>
                        @endforelse
                    </div>

                    <div class="mt-6 pt-6 border-t border-[#D8E1EA]">
                        <a href="{{ route('berita') }}" class="block w-full py-2.5 text-center text-[14px] font-semibold text-[#0E7C9E] bg-[#EEF3F8] hover:bg-[#0E7C9E] hover:text-white rounded-lg transition-colors">
                            Lihat Semua Berita
                        </a>
                    </div>
                </div>
            </aside>
            
        </div>
    </div>
</div>
@endsection
