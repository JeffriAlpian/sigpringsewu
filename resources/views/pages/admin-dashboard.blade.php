@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 min-h-[calc(100vh-56px)]">
    <div class="bg-[#123B6D] text-white rounded-xl shadow-sm p-8 mb-6">
        <h1 class="text-2xl font-bold mb-2">Halo Admin, {{ auth()->user()->name }}!</h1>
        <p class="text-[15px] opacity-80">Selamat datang di Dasbor Admin Dinas. Anda dapat mengelola seluruh data destinasi wisata dan UMKM dari halaman ini.</p>
        
        <form method="POST" action="{{ route('logout') }}" class="mt-8">
            @csrf
            <button type="submit" class="px-6 py-2.5 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-lg transition-colors">
                Keluar
            </button>
        </form>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl border border-[#D8E1EA] shadow-sm">
            <h3 class="text-[#4A5A6B] text-[14px] font-semibold mb-1">Total Destinasi</h3>
            <p class="text-[28px] font-bold text-[#12181F]">{{ \App\Models\Destination::count() }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-[#D8E1EA] shadow-sm">
            <h3 class="text-[#4A5A6B] text-[14px] font-semibold mb-1">Total Pengguna</h3>
            <p class="text-[28px] font-bold text-[#12181F]">{{ \App\Models\User::count() }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-[#D8E1EA] shadow-sm">
            <h3 class="text-[#4A5A6B] text-[14px] font-semibold mb-1">Berita Aktif</h3>
            <p class="text-[28px] font-bold text-[#12181F]">{{ \App\Models\Berita::count() }}</p>
        </div>
    </div>
</div>
@endsection
