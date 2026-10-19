<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Pringsewu.com</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>
<body class="h-full font-sans antialiased text-[#12181F]" x-data="{ sidebarOpen: false }">
    <div class="min-h-full flex flex-col lg:flex-row">
        <!-- Mobile Sidebar Backdrop -->
        <div x-show="sidebarOpen" 
             x-cloak
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-black/50 lg:hidden transition-opacity"
             aria-hidden="true"></div>

        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-[#123B6D] text-white flex flex-col justify-between transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto lg:z-auto"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
            <div>
                <!-- Brand / Logo -->
                <div class="h-16 flex items-center justify-between px-6 border-b border-white/10 bg-[#0c294d]">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                        <span class="text-[18px] font-bold tracking-tight text-white">
                            Pringsewu<span class="text-[#38bdf8]">.com</span>
                        </span>
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-[#0E7C9E] text-white rounded">
                            Admin
                        </span>
                    </a>
                    <button @click="sidebarOpen = false" type="button" class="lg:hidden text-white/70 hover:text-white p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <div class="px-4 py-6 space-y-1">
                    <p class="px-3 text-[11px] font-semibold uppercase tracking-wider text-white/50 mb-2">Menu Utama</p>

                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[14px] font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#0E7C9E] text-white shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dasbor Ringkasan</span>
                    </a>

                    <!-- Destinasi (Wisata & UMKM) -->
                    <a href="{{ route('admin.destinations.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[14px] font-medium transition-colors {{ request()->routeIs('admin.destinations.*') ? 'bg-[#0E7C9E] text-white shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="flex-1">Wisata & UMKM</span>
                        <span class="px-2 py-0.5 text-[11px] font-semibold bg-white/10 text-white rounded-full">
                            {{ \App\Models\Destination::count() }}
                        </span>
                    </a>

                    <!-- Berita & Artikel -->
                    <a href="{{ route('admin.berita.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[14px] font-medium transition-colors {{ request()->routeIs('admin.berita.*') ? 'bg-[#0E7C9E] text-white shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        <span class="flex-1">Berita & Artikel</span>
                        <span class="px-2 py-0.5 text-[11px] font-semibold bg-white/10 text-white rounded-full">
                            {{ \App\Models\Berita::count() }}
                        </span>
                    </a>

                    <!-- Users -->
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[14px] font-medium transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-[#0E7C9E] text-white shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span class="flex-1">Pengguna & Admin</span>
                        <span class="px-2 py-0.5 text-[11px] font-semibold bg-white/10 text-white rounded-full">
                            {{ \App\Models\User::count() }}
                        </span>
                    </a>

                    <div class="pt-6">
                        <p class="px-3 text-[11px] font-semibold uppercase tracking-wider text-white/50 mb-2">Tautan Luar</p>
                        <a href="{{ route('beranda') }}" target="_blank" 
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium text-white/70 hover:bg-white/10 hover:text-white transition-colors">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            <span>Lihat Website Publik</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- User Info & Logout -->
            <div class="p-4 border-t border-white/10 bg-[#0c294d]/60">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-[#0E7C9E] flex items-center justify-center font-bold text-white text-sm shadow">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-[13px] font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-white/60 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-[13px] font-medium bg-red-600/80 hover:bg-red-600 text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navbar -->
            <header class="h-16 bg-white border-b border-[#D8E1EA] flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" type="button" class="lg:hidden p-2 text-gray-600 hover:text-gray-900 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-lg font-bold text-[#12181F]">@yield('header_title', 'Dasbor Pengelolaan')</h1>
                        <p class="text-xs text-[#4A5A6B]">Sistem Informasi Geografis Destinasi Wisata & UMKM Pringsewu</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('beranda') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-semibold text-[#0E7C9E] bg-[#EEF3F8] hover:bg-[#dce7f2] transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Kunjungi Web
                    </a>
                    <div class="h-8 w-px bg-gray-200 hidden sm:block"></div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800">
                            {{ auth()->user()->role === 'admin' ? 'Admin Dinas' : 'User' }}
                        </span>
                        <span class="text-sm font-semibold text-[#12181F] hidden md:inline">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </header>

            <!-- Main Page Body -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-gray-50">
                <!-- Flash Notification Messages -->
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" type="button" class="text-emerald-600 hover:text-emerald-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" type="button" class="text-red-600 hover:text-red-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 shadow-sm">
                        <div class="flex items-center gap-2 mb-2 font-semibold text-sm">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>Harap periksa kesalahan berikut pada formulir:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs space-y-1 pl-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- AI Chatbot Floating Widget -->
    @include('components.chatbot')

    @stack('scripts')
</body>
</html>
