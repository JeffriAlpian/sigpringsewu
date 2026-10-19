@extends('layouts.admin')

@section('title', 'Tambah Pengguna Baru')
@section('header_title', 'Tambah Pengguna / Administrator')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-[#12181F]">Formulir Pengguna Baru</h2>
            <p class="text-xs text-[#4A5A6B]">Tambahkan administrator dinas baru atau akun pengguna biasa</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg border border-[#D8E1EA] text-[#4A5A6B] hover:bg-gray-100 transition-colors">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white p-6 rounded-xl border border-[#D8E1EA] shadow-sm space-y-4">
            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Alamat Email <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="nama@pringsewu.go.id" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
            </div>

            <!-- Role Selector -->
            <div>
                <label for="role" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Peran / Hak Akses (Role) <span class="text-red-500">*</span></label>
                <select id="role" name="role" required class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E] bg-white">
                    <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Pengguna Biasa (Akses Publik)</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin Dinas (Akses Penuh Kelola Konten)</option>
                </select>
                <p class="text-[11px] text-gray-400 mt-1">Admin Dinas memiliki akses untuk mengelola seluruh data destinasi, UMKM, berita, dan pengguna.</p>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Kata Sandi (Minimal 8 karakter) <span class="text-red-500">*</span></label>
                <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-lg border border-[#D8E1EA] text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#0E7C9E] hover:bg-[#0b627d] text-white text-sm font-semibold shadow-md transition-colors">
                Simpan Pengguna
            </button>
        </div>
    </form>
</div>
@endsection
