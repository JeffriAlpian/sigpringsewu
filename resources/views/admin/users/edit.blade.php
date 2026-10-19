@extends('layouts.admin')

@section('title', 'Sunting Pengguna: ' . $user->name)
@section('header_title', 'Sunting Akun Pengguna')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-[#12181F]">Sunting Akun: {{ $user->name }}</h2>
            <p class="text-xs text-[#4A5A6B]">Perbarui informasi profil, alamat surel, peran, atau kata sandi</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg border border-[#D8E1EA] text-[#4A5A6B] hover:bg-gray-100 transition-colors">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white p-6 rounded-xl border border-[#D8E1EA] shadow-sm space-y-4">
            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Alamat Email <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
            </div>

            <!-- Role Selector -->
            <div>
                <label for="role" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Peran / Hak Akses (Role) <span class="text-red-500">*</span></label>
                @if($user->id === auth()->id())
                    <input type="hidden" name="role" value="admin">
                    <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 font-semibold flex items-center justify-between">
                        <span>Admin Dinas (Akun Anda Saat Ini)</span>
                        <span class="text-xs text-amber-600 font-normal">Tidak dapat diturunkan</span>
                    </div>
                @else
                    <select id="role" name="role" required class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E] bg-white">
                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Pengguna Biasa (Akses Publik)</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin Dinas (Akses Penuh Kelola Konten)</option>
                    </select>
                @endif
            </div>

            <!-- Password -->
            <div class="pt-2 border-t border-gray-100">
                <label for="password" class="block text-xs font-semibold text-[#4A5A6B] mb-1">Kata Sandi Baru (Opsional)</label>
                <input type="password" id="password" name="password" placeholder="Biarkan kosong jika tidak ingin mengubah sandi" class="w-full px-3 py-2 text-sm rounded-lg border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E]">
                <p class="text-[11px] text-gray-400 mt-1">Isi hanya jika pengguna meminta penggantian kata sandi baru (minimal 8 karakter).</p>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-lg border border-[#D8E1EA] text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#0E7C9E] hover:bg-[#0b627d] text-white text-sm font-semibold shadow-md transition-colors">
                Perbarui Pengguna
            </button>
        </div>
    </form>
</div>
@endsection
