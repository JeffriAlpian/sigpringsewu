@extends('layouts.app')

@php
$images = is_array($destination->images) && count($destination->images) > 0 ? $destination->images : [$destination->image ?? 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop&auto=format'];
$tags = is_array($destination->tags) ? $destination->tags : [];
@endphp

@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8"
      x-data="{
          activeImg: 0,
          images: {{ json_encode($images) }},
          activeTab: 'deskripsi',
          reportSent: false,
          reportText: '',
          submitReport() {
              if (this.reportText.trim().length >= 10) {
                  this.reportSent = true;
                  this.reportText = '';
              }
          }
      }">
    
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-[13px] mb-6" style="color: #4A5A6B">
        <a href="{{ route('beranda') }}" style="color: #0E7C9E">Beranda</a>
        <span>/</span>
        <a href="{{ route('daftar-produk') }}" style="color: #0E7C9E">{{ $destination->category }}</a>
        <span>/</span>
        <span style="color: #12181F">{{ $destination->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <!-- Gallery — left column -->
        <div class="lg:col-span-3">
            <div class="relative w-full overflow-hidden bg-gray-100" style="padding-bottom: 66.67%; border-radius: 6px; border: 1px solid #D8E1EA">
                <img :src="images[activeImg]" alt="{{ $destination->name }}" class="absolute inset-0 w-full h-full object-cover" style="border-radius: 6px" />
            </div>

            <!-- Thumbnails -->
            <template x-if="images.length > 1">
                <div class="flex gap-2 mt-3 overflow-x-auto">
                    <template x-for="(img, i) in images" :key="i">
                        <button @click="activeImg = i" class="overflow-hidden flex-shrink-0" :style="`width: 72px; height: 54px; border-radius: 4px; border: 2px solid ${activeImg === i ? '#0E7C9E' : '#D8E1EA'}; transition: border-color 120ms ease-out;`">
                            <img :src="img" class="w-full h-full object-cover" />
                        </button>
                    </template>
                </div>
            </template>

            <!-- Tags -->
            <div class="flex flex-wrap gap-2 mt-4">
                @foreach($tags as $tag)
                <span class="text-[12px] font-semibold px-3 py-1" style="background: #EEF3F8; color: #123B6D; border-radius: 4px; border: 1px solid #D8E1EA">
                    {{ $tag }}
                </span>
                @endforeach
            </div>
        </div>

        <!-- Info — right column -->
        <div class="lg:col-span-2 flex flex-col gap-6">
            <!-- Header -->
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-[12px] font-semibold uppercase tracking-wide px-2 py-0.5" style="background: #EEF3F8; color: #0E7C9E; border-radius: 4px">
                        {{ $destination->category }}
                    </span>
                    <span class="text-[12px] font-semibold px-2 py-0.5" style="background: {{ $destination->status === 'Buka' ? '#EEF3F8' : '#FEF2F2' }}; color: {{ $destination->status === 'Buka' ? '#0E7C9E' : '#9B1C1C' }}; border-radius: 4px">
                        {{ $destination->status }}
                    </span>
                </div>
                <h1 class="text-[28px] font-bold leading-tight mb-2" style="color: #12181F">{{ $destination->name }}</h1>
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-[16px] font-semibold" style="color: #0E7C9E">★ {{ number_format($destination->rating, 1) }}</span>
                    <span class="text-[14px]" style="color: #4A5A6B">{{ $destination->review_count }} ulasan</span>
                </div>
                <p class="text-[14px] flex items-center gap-1.5" style="color: #4A5A6B">
                    <svg width="13" height="13" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M8 1.5A4.5 4.5 0 0 0 3.5 6c0 3.5 4.5 8.5 4.5 8.5S12.5 9.5 12.5 6A4.5 4.5 0 0 0 8 1.5ZM8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4Z" fill="#4A5A6B"/></svg>
                    {{ $destination->address }}
                </p>
            </div>

            <!-- Ticket prices -->
            <div style="border: 1px solid #D8E1EA; border-radius: 6px">
                <div class="px-4 py-3" style="border-bottom: 1px solid #D8E1EA">
                    <p class="text-[13px] font-semibold uppercase tracking-wide" style="color: #4A5A6B">Harga Tiket</p>
                </div>
                <div class="flex">
                    <div class="flex-1 px-4 py-3" style="border-right: 1px solid #D8E1EA">
                        <p class="text-[12px] mb-1" style="color: #4A5A6B">Weekday</p>
                        <p class="text-[16px] font-semibold" style="color: #12181F">
                            {{ $destination->weekday_price ? 'Rp ' . number_format($destination->weekday_price, 0, ',', '.') : 'Gratis' }}
                        </p>
                    </div>
                    <div class="flex-1 px-4 py-3">
                        <p class="text-[12px] mb-1" style="color: #4A5A6B">Weekend</p>
                        <p class="text-[16px] font-semibold" style="color: #12181F">
                            {{ $destination->weekend_price ? 'Rp ' . number_format($destination->weekend_price, 0, ',', '.') : 'Gratis' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col gap-2">
                @php
                    $waNumber = preg_replace('/\D/', '', $destination->phone ?? '');
                    $waText = urlencode("Halo, saya ingin bertanya tentang {$destination->name}");
                @endphp
                <a href="https://wa.me/{{ $waNumber }}?text={{ $waText }}" target="_blank" class="flex items-center justify-center gap-2 px-5 py-3 text-[15px] font-semibold" style="border: 1px solid #D8E1EA; color: #123B6D; border-radius: 6px; background: transparent">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="#25D366" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" /></svg>
                    Hubungi via WhatsApp
                </a>
                <button onclick="window.location.href='{{ route('peta') }}'" class="flex items-center justify-center gap-2 px-5 py-3 text-[15px] font-semibold" style="background: #0E7C9E; color: #ffffff; border-radius: 6px">
                    <svg width="15" height="15" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M8 1.5A4.5 4.5 0 0 0 3.5 6c0 3.5 4.5 8.5 4.5 8.5S12.5 9.5 12.5 6A4.5 4.5 0 0 0 8 1.5ZM8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4Z" fill="white" /></svg>
                    Lihat di Peta
                </button>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mt-10">
        <div class="flex gap-0" style="border-bottom: 1px solid #D8E1EA">
            <button @click="activeTab = 'deskripsi'" class="relative px-5 pb-3 pt-2 text-[15px] font-semibold" :class="activeTab === 'deskripsi' ? 'text-[#0E7C9E]' : 'text-[#4A5A6B]'">
                Deskripsi
                <span x-show="activeTab === 'deskripsi'" class="absolute bottom-[-1px] left-0 right-0 h-0.5 bg-[#0E7C9E]" x-cloak></span>
            </button>
            <button @click="activeTab = 'jam-kontak'" class="relative px-5 pb-3 pt-2 text-[15px] font-semibold" :class="activeTab === 'jam-kontak' ? 'text-[#0E7C9E]' : 'text-[#4A5A6B]'">
                Jam & Kontak
                <span x-show="activeTab === 'jam-kontak'" class="absolute bottom-[-1px] left-0 right-0 h-0.5 bg-[#0E7C9E]" x-cloak></span>
            </button>
            <button @click="activeTab = 'laporkan'" class="relative px-5 pb-3 pt-2 text-[15px] font-semibold" :class="activeTab === 'laporkan' ? 'text-[#0E7C9E]' : 'text-[#4A5A6B]'">
                Laporkan Kesalahan
                <span x-show="activeTab === 'laporkan'" class="absolute bottom-[-1px] left-0 right-0 h-0.5 bg-[#0E7C9E]" x-cloak></span>
            </button>
        </div>

        <div class="py-6 max-w-2xl">
            <!-- Tab Content: Deskripsi -->
            <div x-show="activeTab === 'deskripsi'" x-cloak>
                <p class="text-[16px] leading-relaxed" style="color: #12181F">
                    {{ $destination->long_description }}
                </p>
            </div>

            <!-- Tab Content: Jam & Kontak -->
            <div x-show="activeTab === 'jam-kontak'" x-cloak>
                <div class="flex flex-col gap-4">
                    <div class="flex items-start gap-3 p-4" style="border: 1px solid #D8E1EA; border-radius: 6px">
                        <span class="text-[18px]">🕐</span>
                        <div>
                            <p class="text-[13px] font-semibold uppercase tracking-wide mb-1" style="color: #4A5A6B">Jam Operasional</p>
                            <p class="text-[16px] font-semibold" style="color: #12181F">{{ $destination->open_hours }}</p>
                            <p class="text-[13px] mt-1 flex items-center gap-1" style="color: {{ $destination->status === 'Buka' ? '#0E7C9E' : '#9B1C1C' }}">
                                <span class="inline-block w-2 h-2 rounded-full" style="background: {{ $destination->status === 'Buka' ? '#0E7C9E' : '#9B1C1C' }}"></span>
                                Status: {{ $destination->status }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4" style="border: 1px solid #D8E1EA; border-radius: 6px">
                        <span class="text-[18px]">📍</span>
                        <div>
                            <p class="text-[13px] font-semibold uppercase tracking-wide mb-1" style="color: #4A5A6B">Alamat Lengkap</p>
                            <p class="text-[16px]" style="color: #12181F">{{ $destination->address }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4" style="border: 1px solid #D8E1EA; border-radius: 6px">
                        <span class="text-[18px]">📞</span>
                        <div>
                            <p class="text-[13px] font-semibold uppercase tracking-wide mb-1" style="color: #4A5A6B">Kontak</p>
                            <a href="tel:{{ $destination->phone }}" class="text-[16px] font-semibold text-[#0E7C9E]">{{ $destination->phone }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Laporkan -->
            <div x-show="activeTab === 'laporkan'" x-cloak>
                <div x-show="reportSent" class="p-4 rounded-md" style="background: #EEF3F8; border: 1px solid #D8E1EA; border-radius: 6px">
                    <p class="text-[15px] font-semibold mb-1" style="color: #12181F">Laporan terkirim</p>
                    <p class="text-[14px]" style="color: #4A5A6B">Terima kasih. Tim kami akan meninjau laporan Anda dan memperbarui data jika diperlukan.</p>
                    <button @click="reportSent = false" class="mt-3 text-[14px] font-semibold text-[#0E7C9E]">Kirim laporan lain</button>
                </div>

                <form x-show="!reportSent" @submit.prevent="submitReport" class="flex flex-col gap-4">
                    <p class="text-[14px]" style="color: #4A5A6B">Temukan data yang salah atau sudah berubah? Laporkan di sini agar tim kami bisa memperbarui.</p>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[13px] font-semibold" style="color: #4A5A6B">Jenis Kesalahan</label>
                        <select class="px-3 py-2.5 text-[15px] bg-white outline-none" style="border: 1px solid #D8E1EA; border-radius: 4px; color: #12181F;">
                            <option>Informasi jam operasional tidak akurat</option>
                            <option>Alamat atau lokasi salah</option>
                            <option>Harga tiket berubah</option>
                            <option>Nomor telepon tidak aktif</option>
                            <option>Destinasi sudah tutup permanen</option>
                            <option>Lainnya</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[13px] font-semibold" style="color: #4A5A6B">Detail Laporan</label>
                        <textarea x-model="reportText" rows="4" placeholder="Jelaskan kesalahan data secara singkat…" class="px-3 py-2.5 text-[15px] bg-white outline-none resize-none focus:border-[#0E7C9E] focus:border-2" style="border: 1px solid #D8E1EA; border-radius: 4px; color: #12181F;"></textarea>
                    </div>
                    <button type="submit" :disabled="reportText.trim().length < 10" class="self-start px-5 py-2.5 text-[14px] font-semibold disabled:opacity-50" style="background: #0E7C9E; color: #ffffff; border-radius: 6px;">
                        Kirim Laporan
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
