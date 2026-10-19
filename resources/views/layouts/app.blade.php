<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Pringsewu.com') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>
<body class="antialiased bg-gray-50 text-gray-900 min-h-screen flex flex-col">
    <!-- Navbar -->
    <header x-data="{ mobileMenuOpen: false, scrolled: false }" 
            @scroll.window="scrolled = (window.pageYOffset > 20)"
            class="fixed w-full top-0 z-50 transition-all duration-300"
            :class="(scrolled || !{{ request()->routeIs('beranda') ? 'true' : 'false' }}) ? 'bg-white shadow-sm border-b border-[#D8E1EA]' : 'bg-transparent border-transparent text-white'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                <!-- Logo -->
                <a href="{{ route('beranda') }}" class="flex items-center gap-2 cursor-pointer transition-colors"
                   :class="(scrolled || !{{ request()->routeIs('beranda') ? 'true' : 'false' }}) ? '' : 'text-white'">
                    <span class="text-[17px] font-bold tracking-tight" :style="(scrolled || !{{ request()->routeIs('beranda') ? 'true' : 'false' }}) ? 'color: #123B6D' : 'color: white'">
                        Pringsewu<span :style="(scrolled || !{{ request()->routeIs('beranda') ? 'true' : 'false' }}) ? 'color: #0E7C9E' : 'color: rgba(255,255,255,0.7)'">.com</span>
                    </span>
                </a>

                <!-- Desktop nav -->
                <nav class="hidden md:flex items-center gap-1">
                    <a href="{{ route('beranda') }}" class="relative px-3 py-4 text-[15px] font-semibold transition-colors duration-150"
                       :class="(scrolled || !{{ request()->routeIs('beranda') ? 'true' : 'false' }}) ? '{{ request()->routeIs('beranda') ? 'text-[#0E7C9E]' : 'text-[#4A5A6B]' }}' : 'text-white hover:text-white/80'">
                        Beranda
                        @if(request()->routeIs('beranda'))
                            <span class="absolute bottom-0 left-3 right-3 h-0.5 rounded-full" :style="(scrolled || !{{ request()->routeIs('beranda') ? 'true' : 'false' }}) ? 'background: #0E7C9E' : 'background: white'"></span>
                        @endif
                    </a>
                    <a href="{{ route('peta') }}" class="relative px-3 py-4 text-[15px] font-semibold transition-colors duration-150"
                       :class="(scrolled || !{{ request()->routeIs('beranda') ? 'true' : 'false' }}) ? '{{ request()->routeIs('peta') ? 'text-[#0E7C9E]' : 'text-[#4A5A6B]' }}' : 'text-white/80 hover:text-white'">
                        Peta Wisata
                    </a>
                    <a href="{{ route('daftar-produk') }}" class="relative px-3 py-4 text-[15px] font-semibold transition-colors duration-150"
                       :class="(scrolled || !{{ request()->routeIs('beranda') ? 'true' : 'false' }}) ? '{{ request()->routeIs('daftar-produk') ? 'text-[#0E7C9E]' : 'text-[#4A5A6B]' }}' : 'text-white/80 hover:text-white'">
                        Daftar Produk
                    </a>
                    <a href="{{ route('berita') }}" class="relative px-3 py-4 text-[15px] font-semibold transition-colors duration-150"
                       :class="(scrolled || !{{ request()->routeIs('beranda') ? 'true' : 'false' }}) ? '{{ request()->routeIs('berita') ? 'text-[#0E7C9E]' : 'text-[#4A5A6B]' }}' : 'text-white/80 hover:text-white'">
                        Berita
                    </a>
                </nav>

                <!-- Desktop CTA -->
                <div class="hidden md:flex items-center gap-2">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-1.5 text-[13px] font-semibold rounded-md transition-all duration-150 flex items-center gap-1.5"
                               :class="(scrolled || !{{ request()->routeIs('beranda') ? 'true' : 'false' }}) ? 'bg-[#123B6D] text-white hover:bg-[#0c294d]' : 'bg-white/20 hover:bg-white/30 text-white backdrop-blur-sm'">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Panel Admin
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="px-3.5 py-1.5 text-[13px] font-semibold rounded-md transition-all duration-150"
                               :class="(scrolled || !{{ request()->routeIs('beranda') ? 'true' : 'false' }}) ? 'bg-[#0E7C9E] text-white hover:bg-[#0b627d]' : 'bg-white/20 hover:bg-white/30 text-white backdrop-blur-sm'">
                                Dashboard
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-2.5 py-1.5 text-[13px] font-semibold rounded-md transition-all duration-150"
                                    :class="(scrolled || !{{ request()->routeIs('beranda') ? 'true' : 'false' }}) ? 'text-red-600 hover:bg-red-50' : 'text-white/80 hover:text-white'">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-1.5 text-[14px] font-semibold rounded-md transition-all duration-150" 
                           :class="(scrolled || !{{ request()->routeIs('beranda') ? 'true' : 'false' }}) ? 'bg-[#0E7C9E] text-white hover:bg-[#0b627d]' : 'bg-white/20 hover:bg-white/30 text-white backdrop-blur-sm'"
                           style="border-radius: 6px;">
                            Masuk
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="md:hidden p-2 focus:outline-none"
                        :class="(scrolled || !{{ request()->routeIs('beranda') ? 'true' : 'false' }}) ? 'text-gray-500 hover:text-gray-900' : 'text-white'">
                    <svg x-show="!mobileMenuOpen" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-gray-200 bg-white shadow-lg">
            <div class="pt-2 pb-4 space-y-1 px-4">
                <a href="{{ route('beranda') }}" class="block px-3 py-2 rounded-md text-[15px] font-semibold {{ request()->routeIs('beranda') ? 'text-[#0E7C9E] bg-[#EEF3F8]' : 'text-[#4A5A6B]' }}">
                    Beranda
                </a>
                <a href="{{ route('peta') }}" class="block px-3 py-2 rounded-md text-[15px] font-semibold {{ request()->routeIs('peta') ? 'text-[#0E7C9E] bg-[#EEF3F8]' : 'text-[#4A5A6B]' }}">
                    Peta Wisata
                </a>
                <a href="{{ route('daftar-produk') }}" class="block px-3 py-2 rounded-md text-[15px] font-semibold {{ request()->routeIs('daftar-produk') ? 'text-[#0E7C9E] bg-[#EEF3F8]' : 'text-[#4A5A6B]' }}">
                    Daftar Produk
                </a>
                <a href="{{ route('berita') }}" class="block px-3 py-2 rounded-md text-[15px] font-semibold {{ request()->routeIs('berita') ? 'text-[#0E7C9E] bg-[#EEF3F8]' : 'text-[#4A5A6B]' }}">
                    Berita
                </a>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 mt-4 rounded-md text-[15px] font-semibold text-center bg-[#123B6D] text-white">
                            Panel Admin
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 mt-4 rounded-md text-[15px] font-semibold text-center bg-[#0E7C9E] text-white">
                            Dashboard
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full px-3 py-2 rounded-md text-[14px] font-semibold text-center text-red-600 bg-red-50">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 mt-4 rounded-md text-[15px] font-semibold text-center" style="background: #0E7C9E; color: #ffffff;">
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <div class="flex-1 flex flex-col {{ request()->routeIs('beranda') ? '' : 'pt-14' }}">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="py-6" style="border-top: 1px solid #D8E1EA; background: #EEF3F8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <p class="text-[15px] font-bold" style="color: #123B6D">
                        Pringsewu<span style="color: #0E7C9E">.com</span>
                    </p>
                    <p class="text-[13px] mt-0.5" style="color: #4A5A6B">
                        Sistem Informasi Geografis Destinasi Wisata & UMKM Kabupaten Pringsewu
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('beranda') }}" class="text-[13px] capitalize" style="color: #4A5A6B">Beranda</a>
                    <a href="{{ route('peta') }}" class="text-[13px] capitalize" style="color: #4A5A6B">Peta</a>
                    <a href="{{ route('daftar-produk') }}" class="text-[13px] capitalize" style="color: #4A5A6B">Daftar Produk</a>
                    <a href="{{ route('berita') }}" class="text-[13px] capitalize" style="color: #4A5A6B">Berita</a>
                </div>
            </div>
            <div class="mt-4 pt-4 flex items-center justify-between" style="border-top: 1px solid #D8E1EA">
                <p class="text-[12px]" style="color: #4A5A6B">
                    © 2026 Dinas Pariwisata Kabupaten Pringsewu · Data diperbarui secara berkala
                </p>
                <p class="text-[12px]" style="color: #4A5A6B">
                    v1.0.0
                </p>
            </div>
        </div>
    </footer>

    <!-- AI Chatbot Floating Widget -->
    @include('components.chatbot')

    @stack('scripts')
</body>
</html>

