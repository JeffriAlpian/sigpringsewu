@extends('layouts.admin')

@section('title', 'Manajemen Pengguna & Admin')
@section('header_title', 'Kelola Pengguna Sistem')

@section('content')
<div class="space-y-6">
    <!-- Action and Filter Bar -->
    <div class="bg-white p-5 rounded-xl border border-[#D8E1EA] shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-[#12181F]">Daftar Pengguna & Hak Akses</h2>
                <p class="text-xs text-[#4A5A6B]">Kelola akun administrator dinas dan pengguna terdaftar pada platform</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold bg-[#0E7C9E] text-white hover:bg-[#0b627d] shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Tambah Pengguna Baru
            </a>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="mt-4 pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama atau email..." class="w-full pl-9 pr-4 py-2 text-xs rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <div class="w-full sm:w-48">
                <select name="role" class="w-full px-3 py-2 text-xs rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E] bg-white">
                    <option value="">Semua Peran (Role)</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin Dinas</option>
                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Pengguna Biasa</option>
                </select>
            </div>
            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-[#123B6D] text-white rounded-lg text-xs font-semibold hover:bg-[#0e2f57] transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['search', 'role']))
                <a href="{{ route('admin.users.index') }}" class="p-2 text-gray-500 hover:text-red-600 rounded-lg border border-gray-200 hover:bg-gray-50" title="Reset filter">
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
                        <th class="px-4 py-3.5">Pengguna</th>
                        <th class="px-4 py-3.5">Email</th>
                        <th class="px-4 py-3.5">Hak Akses (Role)</th>
                        <th class="px-4 py-3.5">Metode Daftar</th>
                        <th class="px-4 py-3.5">Terdaftar Sejak</th>
                        <th class="px-4 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/80 transition-colors {{ $user->id === auth()->id() ? 'bg-blue-50/40' : '' }}">
                            <!-- Name & Avatar -->
                            <td class="px-4 py-3.5 font-medium">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-[#123B6D] text-white flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="text-sm font-bold text-[#12181F]">{{ $user->name }}</p>
                                            @if($user->id === auth()->id())
                                                <span class="px-1.5 py-0.2 text-[9px] font-bold uppercase bg-blue-100 text-blue-800 rounded">Anda</span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-[#4A5A6B]">ID #{{ $user->id }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="px-4 py-3.5 text-gray-700">
                                {{ $user->email }}
                            </td>

                            <!-- Role -->
                            <td class="px-4 py-3.5">
                                @if($user->role === 'admin')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-100 text-purple-800">
                                        Admin Dinas
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-gray-100 text-gray-800">
                                        Pengguna
                                    </span>
                                @endif
                            </td>

                            <!-- Login Provider -->
                            <td class="px-4 py-3.5 text-gray-500">
                                @if($user->google_id)
                                    <span class="inline-flex items-center gap-1 text-[11px] text-blue-600 font-medium">
                                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M12.24 10.285V14.4h6.806c-.275 1.765-2.056 5.174-6.806 5.174-4.095 0-7.439-3.389-7.439-7.574s3.345-7.574 7.439-7.574c2.33 0 3.891.989 4.785 1.849l3.254-3.138C18.189 1.186 15.479 0 12.24 0c-6.635 0-12 5.365-12 12s5.365 12 12 12c6.926 0 11.52-4.869 11.52-11.726 0-.788-.085-1.39-.189-1.989H12.24z"/></svg>
                                        Google
                                    </span>
                                @else
                                    <span class="text-[11px]">Email & Sandi</span>
                                @endif
                            </td>

                            <!-- Date -->
                            <td class="px-4 py-3.5 text-gray-600 whitespace-nowrap">
                                {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                            </td>

                            <!-- Aksi -->
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="p-1.5 text-blue-600 hover:text-blue-800 rounded hover:bg-blue-50 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ addslashes($user->name) }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-600 hover:text-red-800 rounded hover:bg-red-50 transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="p-1.5 text-gray-300 cursor-not-allowed" title="Tidak dapat menghapus akun sendiri">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                                Tidak ada akun pengguna yang cocok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 bg-gray-50 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
