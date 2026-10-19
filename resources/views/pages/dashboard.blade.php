@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 min-h-[calc(100vh-56px)]">
    <div class="bg-white rounded-xl shadow-sm border border-[#D8E1EA] p-8">
        <h1 class="text-2xl font-bold text-[#12181F] mb-2">Halo, {{ auth()->user()->name }}!</h1>
        <p class="text-[15px] text-[#4A5A6B]">Selamat datang di dasbor Pengguna. Anda dapat mengatur profil dan destinasi favorit Anda di sini.</p>
        
        <div class="mt-8 flex items-center gap-3">
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 bg-[#123B6D] text-white font-semibold rounded-lg hover:bg-[#0c294d] transition-colors text-sm">
                    Buka Panel Administrator
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-5 py-2.5 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors text-sm">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
