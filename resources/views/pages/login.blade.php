@extends('layouts.app')

@section('content')
<div class="flex-1 flex items-center justify-center px-4 py-12" style="background: #EEF3F8"
     x-data="{
        mode: 'login',
        role: 'pengguna',
        submitted: false,
        submitForm() {
            this.submitted = true;
        }
     }">
    <div class="w-full max-w-md">
        
        <!-- SUCCESS SCREEN -->
        <div x-show="submitted" x-cloak class="bg-white p-8 max-w-md w-full text-center shadow-sm" style="border: 1px solid #D8E1EA; border-radius: 10px">
            <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 text-[24px]" style="background: #EEF3F8">✓</div>
            <h2 class="text-[20px] font-semibold mb-2" style="color: #12181F" x-text="mode === 'login' ? 'Berhasil masuk!' : 'Akun berhasil dibuat!'"></h2>
            <p class="text-[14px] mb-6" style="color: #4A5A6B" x-text="mode === 'register' ? 'Akun Anda telah terdaftar dalam sistem.' : 'Selamat datang kembali di Pringsewu.com'"></p>
            <div class="flex flex-col gap-2">
                <button onclick="window.location.href='{{ route('beranda') }}'" class="w-full py-3 text-[15px] font-semibold" style="background: #0E7C9E; color: #ffffff; border-radius: 6px">
                    Ke Beranda
                </button>
            </div>
        </div>

        <!-- FORM SCREEN -->
        <div x-show="!submitted" class="bg-white p-8 shadow-sm" style="border: 1px solid #D8E1EA; border-radius: 10px">
            <!-- Logo -->
            <div class="text-center mb-6">
                <p class="text-[20px] font-bold" style="color: #123B6D">
                    Pringsewu<span style="color: #0E7C9E">.com</span>
                </p>
                <p class="text-[13px] mt-1" style="color: #4A5A6B">
                    SIG Destinasi Wisata & UMKM
                </p>
            </div>

            <!-- Mode toggle -->
            <div class="flex mb-6" style="border: 1px solid #D8E1EA; border-radius: 6px; overflow: hidden">
                <button type="button" @click="mode = 'login'" class="flex-1 py-2.5 text-[14px] font-semibold transition-colors duration-150" :style="mode === 'login' ? 'background: #123B6D; color: #ffffff;' : 'background: transparent; color: #4A5A6B;'">
                    Masuk
                </button>
                <button type="button" @click="mode = 'register'" class="flex-1 py-2.5 text-[14px] font-semibold transition-colors duration-150" :style="mode === 'register' ? 'background: #123B6D; color: #ffffff;' : 'background: transparent; color: #4A5A6B;'">
                    Daftar
                </button>
            </div>

                        <form x-show="mode === 'login'" action="{{ route('login') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                
                @if ($errors->any())
                    <div class="p-3 bg-red-100 text-red-700 text-sm rounded-md">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Email -->
                <div class="flex flex-col gap-1.5">
                    <label for="login_email" class="text-[13px] font-semibold" style="color: #4A5A6B">Email</label>
                    <input id="login_email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required class="px-3 py-2.5 text-[15px] bg-white outline-none focus:border-[#0E7C9E] focus:border-2" style="border: 1px solid #D8E1EA; border-radius: 4px; color: #12181F;" />
                </div>

                <!-- Password -->
                <div class="flex flex-col gap-1.5">
                    <div class="flex justify-between items-center">
                        <label for="login_password" class="text-[13px] font-semibold" style="color: #4A5A6B">Kata Sandi</label>
                        <a href="#" class="text-[12px]" style="color: #0E7C9E">Lupa kata sandi?</a>
                    </div>
                    <input id="login_password" type="password" name="password" placeholder="Minimal 8 karakter" required class="px-3 py-2.5 text-[15px] bg-white outline-none focus:border-[#0E7C9E] focus:border-2" style="border: 1px solid #D8E1EA; border-radius: 4px; color: #12181F;" />
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full py-3 text-[15px] font-semibold mt-1" style="background: #0E7C9E; color: #ffffff; border-radius: 6px">
                    Masuk
                </button>
            </form>

            <form x-show="mode === 'register'" x-cloak action="{{ route('register') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                
                <!-- Role Selector -->
                

                <!-- Name Field -->
                <div class="flex flex-col gap-1.5">
                    <label for="name" class="text-[13px] font-semibold" style="color: #4A5A6B">Nama Lengkap</label>
                    <input id="name" name="name" type="text" placeholder="Masukkan nama lengkap" required class="px-3 py-2.5 text-[15px] bg-white outline-none focus:border-[#0E7C9E] focus:border-2" style="border: 1px solid #D8E1EA; border-radius: 4px; color: #12181F;" />
                </div>

                <!-- Email -->
                <div class="flex flex-col gap-1.5">
                    <label for="register_email" class="text-[13px] font-semibold" style="color: #4A5A6B">Email</label>
                    <input id="register_email" type="email" name="email" placeholder="nama@email.com" required class="px-3 py-2.5 text-[15px] bg-white outline-none focus:border-[#0E7C9E] focus:border-2" style="border: 1px solid #D8E1EA; border-radius: 4px; color: #12181F;" />
                </div>

                <!-- Password -->
                <div class="flex flex-col gap-1.5">
                    <label for="register_password" class="text-[13px] font-semibold" style="color: #4A5A6B">Kata Sandi</label>
                    <input id="register_password" type="password" name="password" placeholder="Minimal 8 karakter" required class="px-3 py-2.5 text-[15px] bg-white outline-none focus:border-[#0E7C9E] focus:border-2" style="border: 1px solid #D8E1EA; border-radius: 4px; color: #12181F;" />
                </div>
                
                <!-- Password Confirmation -->
                <div class="flex flex-col gap-1.5">
                    <label for="password_confirmation" class="text-[13px] font-semibold" style="color: #4A5A6B">Konfirmasi Kata Sandi</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Ulangi kata sandi" required class="px-3 py-2.5 text-[15px] bg-white outline-none focus:border-[#0E7C9E] focus:border-2" style="border: 1px solid #D8E1EA; border-radius: 4px; color: #12181F;" />
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full py-3 text-[15px] font-semibold mt-1" style="background: #0E7C9E; color: #ffffff; border-radius: 6px">
                    Buat Akun
                </button>
            </form>

            <!-- Divider -->
            <div class="flex items-center gap-3 mt-4">
                <span class="flex-1 h-px" style="background: #D8E1EA"></span>
                <span class="text-[12px]" style="color: #4A5A6B">atau</span>
                <span class="flex-1 h-px" style="background: #D8E1EA"></span>
            </div>

            <!-- Google OAuth -->
            <a href="{{ route('google.login') }}" class="w-full mt-4 py-3 text-[15px] font-semibold flex items-center justify-center gap-2 transition-colors hover:bg-gray-50" style="border: 1px solid #D8E1EA; color: #123B6D; border-radius: 6px; background: transparent">
                <svg width="18" height="18" viewBox="0 0 48 48" aria-hidden="true">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z" />
                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z" />
                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z" />
                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z" />
                </svg>
                Lanjutkan dengan Google
            </a>
        </div>

        <!-- Footer note -->
        <p x-show="!submitted" class="text-center text-[13px] mt-4" style="color: #4A5A6B">
            <template x-if="mode === 'login'">
                <span>Belum punya akun? <button @click="mode = 'register'" class="font-semibold" style="color: #0E7C9E">Daftar di sini</button></span>
            </template>
            <template x-if="mode === 'register'">
                <span>Sudah punya akun? <button @click="mode = 'login'" class="font-semibold" style="color: #0E7C9E">Masuk</button></span>
            </template>
        </p>
    </div>
</div>
@endsection



