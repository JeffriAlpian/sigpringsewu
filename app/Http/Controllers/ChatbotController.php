<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Destination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    /**
     * Handle incoming chatbot message.
     */
    public function message(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        $userMessage = trim($validated['message']);
        $lower = strtolower($userMessage);

        // Check for external AI API key (Gemini / OpenAI) if user configured one
        $apiKey = config('services.gemini.key', env('GEMINI_API_KEY'));
        if ($apiKey) {
            $aiReply = $this->callGeminiApi($userMessage, $apiKey);
            if ($aiReply) {
                return response()->json([
                    'status' => 'success',
                    'reply' => $aiReply,
                    'suggestions' => $this->getDefaultSuggestions($lower),
                ]);
            }
        }

        // Knowledge-Engine Response using Local Context and Real-time Services
        $response = $this->generateKnowledgeResponse($userMessage, $lower);

        return response()->json([
            'status' => 'success',
            'reply' => $response['reply'],
            'suggestions' => $response['suggestions'],
        ]);
    }

    /**
     * Generate smart contextual response based on database destinations and news.
     *
     * @return array{reply: string, suggestions: array<int, string>}
     */
    protected function generateKnowledgeResponse(string $rawMessage, string $lower): array
    {
        // 1. Greetings / Introduction
        if ($this->containsAny($lower, ['halo', 'hai', 'hello', 'hey', 'pagi', 'siang', 'sore', 'malam', 'assalam', 'siapa kamu', 'bisa apa'])) {
            $totalWisata = Destination::where('category', 'Wisata')->count();
            $totalUmkm = Destination::where('category', 'UMKM')->count();

            $reply = "Halo! Saya **Si Pringgo**, asisten pintar virtual **Pringsewu.com** 🎋\n\n".
                "Saya dapat membantu Anda menjawab berbagai informasi:\n".
                "• 🌦️ **Prakiraan Cuaca Terkini** di Kabupaten Pringsewu (real-time)\n".
                "• 🏖️ **{$totalWisata} Destinasi Wisata** & 🛍️ **{$totalUmkm} Sentra UMKM**\n".
                "• 🗺️ **Peta Interaktif SIG**, Rute, & Panduan Lokasi\n".
                "• 🎟️ Informasi Harga Tiket Masuk & Jam Operasional\n".
                "• 📜 **Sejarah, Profil, & Transportasi** menuju Pringsewu\n".
                "• 📰 Kabar Berita & Agenda Wisata Terkini\n\n".
                'Ada yang ingin Anda tanyakan hari ini?';

            return [
                'reply' => $reply,
                'suggestions' => ['Cek Cuaca Pringsewu Hari Ini', 'Rekomendasi Wisata Populer', 'Sentra Kuliner & UMKM', 'Peta Destinasi'],
            ];
        }

        // 2. Thank you
        if ($this->containsAny($lower, ['terima kasih', 'makasih', 'thanks', 'matur nuwun', 'syukron'])) {
            return [
                'reply' => 'Sama-sama! Senang bisa membantu Anda. Jika ada hal lain yang ingin Anda ketahui tentang Pringsewu, cuaca, atau destinasi liburan, silakan tanyakan saja! 😊🌿',
                'suggestions' => ['Cek Cuaca Hari Ini', 'Wisata Alam Terbaik', 'Buka Peta Wisata'],
            ];
        }

        // 3. Weather / Cuaca Query (Real-time via Open-Meteo)
        if ($this->containsAny($lower, ['cuaca', 'hujan', 'panas', 'suhu', 'prakiraan', 'mendung', 'iklim', 'deras', 'gerimis'])) {
            return $this->getLiveWeather();
        }

        // 4. Sejarah & Profil Kabupaten Pringsewu
        if ($this->containsAny($lower, ['sejarah', 'asal usul', 'asal-usul', 'berdiri', 'profil pringsewu', 'tentang pringsewu', 'semboyan', 'jejama', 'bambu seribu'])) {
            return [
                'reply' => "📜 **Sekilas Profil & Sejarah Kabupaten Pringsewu**:\n\n".
                    "• **Asal-usul Nama**: Nama *Pringsewu* berasal dari bahasa Jawa yang berarti **\"Bambu Seribu\"** (*Pring* = bambu, *Sewu* = seribu). Konon, saat para perantau pertama kali membuka kawasan ini pada tahun 1925, hutan di tepian Sungai Way Tebu dipenuhi rumpun bambu lebat.\n".
                    "• **Semboyan Daerah**: *\"Jejama Secancanan\"* yang memiliki makna luhur saling bergandengan tangan, bergotong-royong, dan bersatu padu membangun daerah.\n".
                    "• **Status Wilayah**: Diresmikan menjadi kabupaten otonom pada tahun 2008 (pemekaran dari Kabupaten Tanggamus) dan terdiri dari 9 kecamatan.\n".
                    '• **Keunikan**: Pringsewu terkenal dengan suasana kotanya yang ramah, sentra kerajinan Tapis Lugusari, serta persawahan dan perbukitan yang asri.',
                'suggestions' => ['Cek Cuaca Pringsewu', 'Wisata Populer', 'Kampung Tapis Lugusari', 'Peta Lokasi'],
            ];
        }

        // 5. Transportasi & Cara Menuju Pringsewu
        if ($this->containsAny($lower, ['transportasi', 'cara ke', 'rute ke pringsewu', 'dari bandar lampung', 'dari bandara', 'jarak', 'terminal', 'travel', 'akses'])) {
            return [
                'reply' => "🚗 **Panduan Transportasi Menuju Kabupaten Pringsewu**:\n\n".
                    "• **Dari Kota Bandar Lampung**: Berjarak sekitar 38 - 42 km (dapat ditempuh dalam waktu **45 - 60 menit** via Jalan Lintas Barat Sumatera melewati Gedong Tataan).\n".
                    "• **Dari Bandara Radin Inten II (Branti)**: Berjarak sekitar 45 km (dapat ditempuh dalam waktu **1 hingga 1,5 jam** berkendara via Jalan Raya Natar - Branti - Gadingrejo).\n".
                    "• **Pilihan Moda**: Tersedia angkutan bus antar-kota, travel reguler, kendaraan pribadi, maupun layanan transportasi daring (ojek/taksi online) hingga ke pusat kota Pringsewu.\n\n".
                    '📍 Anda dapat membuka [Peta SIG Pringsewu]('.route('peta').') untuk melihat rute dan titik lokasi wisata secara presisi.',
                'suggestions' => ['Buka Peta Wisata', 'Cek Cuaca Hari Ini', 'Wisata Populer'],
            ];
        }

        // 6. Fasilitas Umum & Layanan Penting
        if ($this->containsAny($lower, ['rumah sakit', 'rsud', 'puskesmas', 'darurat', 'masjid', 'rest area', 'spbu', 'pom bensin', 'polsek', 'polres'])) {
            return [
                'reply' => "🏥 **Fasilitas Umum & Layanan Penting di Pringsewu**:\n\n".
                    "• **Layanan Kesehatan**: RSUD Pringsewu (Fajar Agung), RS Mitra Husada, RS Surya Asih, dan Puskesmas di setiap kecamatan.\n".
                    "• **Ikon Persinggahan**: **Rest Area Pringsewu (Wates, Gadingrejo)** — tempat favorit beristirahat dengan kuliner dan spot foto bambu ikonik.\n".
                    "• **Pusat Ibadah**: Masjid Agung Al-Asmaul Husna Pringsewu.\n".
                    '• **Keamanan**: Polres Pringsewu dan Polsek di tiap kecamatan siap melayani masyarakat dan wisatawan.',
                'suggestions' => ['Rest Area Pringsewu', 'Wisata Kuliner Nggruput', 'Buka Peta'],
            ];
        }

        // 7. Search for specific destination by exact / partial name
        $allDestinations = Destination::all();
        $matchedDestination = $allDestinations->first(function ($dest) use ($lower) {
            $destName = strtolower($dest->name);

            return str_contains($lower, $destName) || str_contains($destName, $lower);
        });

        if ($matchedDestination) {
            $priceText = 'Gratis';
            if ($matchedDestination->weekday_price || $matchedDestination->weekend_price) {
                $priceText = 'Hari biasa: Rp '.number_format($matchedDestination->weekday_price, 0, ',', '.').
                    ' · Akhir pekan: Rp '.number_format($matchedDestination->weekend_price, 0, ',', '.');
            }

            $tagsText = is_array($matchedDestination->tags) ? implode(', ', $matchedDestination->tags) : '-';

            $reply = "📍 **{$matchedDestination->name}** ({$matchedDestination->category})\n\n".
                "⭐ **Rating**: {$matchedDestination->rating}/5.0 ({$matchedDestination->review_count} ulasan)\n".
                "📌 **Lokasi**: {$matchedDestination->location}, Kec. {$matchedDestination->district}\n".
                "🕒 **Jam Buka**: {$matchedDestination->open_hours} (Status: {$matchedDestination->status})\n".
                "🎟️ **Tarif Tiket**: {$priceText}\n".
                "🏷️ **Kategori/Label**: {$tagsText}\n\n".
                '📝 **Deskripsi**: '.($matchedDestination->description ?? 'Destinasi menarik di Pringsewu.')."\n\n".
                '👉 [Klik di sini untuk melihat halaman detail lengkap]('.route('detail', $matchedDestination->id).') atau lihat di [Peta Interaktif]('.route('peta').').';

            return [
                'reply' => $reply,
                'suggestions' => ['Cek Cuaca Hari Ini', 'Destinasi Lain di '.$matchedDestination->district, 'Rute di Peta'],
            ];
        }

        // 8. District Query (Kecamatan)
        $districts = ['gadingrejo', 'sukoharjo', 'ambarawa', 'pagelaran', 'pardasuka', 'banyumas', 'adiluwih', 'pagelaran utara'];
        if (str_contains($lower, 'kecamatan pringsewu') || str_contains($lower, 'kec. pringsewu')) {
            $districts[] = 'pringsewu';
        }
        foreach ($districts as $district) {
            if (str_contains($lower, $district)) {
                $inDistrict = Destination::where('district', 'like', "%{$district}%")->get();
                $districtTitle = ucwords($district);

                if ($inDistrict->isNotEmpty()) {
                    $list = $inDistrict->map(function ($d) {
                        return "• [{$d->name}](".route('detail', $d->id).") ({$d->category}) - ⭐ {$d->rating} ({$d->location})";
                    })->implode("\n");

                    $reply = "Ditemukan **{$inDistrict->count()} tempat** menarik di wilayah **Kecamatan {$districtTitle}**:\n\n".
                        $list."\n\n".
                        '🗺️ Anda juga bisa memantau titik koordinatnya secara langsung di [Peta Interaktif Pringsewu]('.route('peta').').';

                    return [
                        'reply' => $reply,
                        'suggestions' => ['Cek Cuaca Hari Ini', 'Wisata Keluarga', 'Sentra UMKM'],
                    ];
                }

                return [
                    'reply' => "Saat ini belum ada data destinasi terdaftar khusus di Kecamatan {$districtTitle}. Anda dapat menjelajahi kecamatan tetangga seperti Pringsewu, Gadingrejo, atau Sukoharjo!",
                    'suggestions' => ['Wisata di Sukoharjo', 'Wisata di Gadingrejo', 'Cek Cuaca Hari Ini'],
                ];
            }
        }

        // 9. Price / Ticket Query
        if ($this->containsAny($lower, ['tiket', 'harga', 'biaya', 'tarif', 'bayar', 'gratis', 'murah'])) {
            $destinationsWithPrice = Destination::whereNotNull('weekday_price')
                ->where('weekday_price', '>', 0)
                ->orderBy('weekday_price')
                ->take(5)
                ->get();

            $freeDestinations = Destination::where(function ($q) {
                $q->whereNull('weekday_price')->orWhere('weekday_price', 0);
            })->take(4)->get();

            $reply = "🎟️ **Informasi Tarif & Tiket Masuk di Pringsewu**:\n\n";

            if ($freeDestinations->isNotEmpty()) {
                $reply .= "🆓 **Destinasi Gratis / Tanpa Tiket Masuk**:\n";
                foreach ($freeDestinations as $f) {
                    $reply .= "• [{$f->name}](".route('detail', $f->id).") - Kec. {$f->district}\n";
                }
                $reply .= "\n";
            }

            if ($destinationsWithPrice->isNotEmpty()) {
                $reply .= "💰 **Tarif Destinasi Populer Terjangkau**:\n";
                foreach ($destinationsWithPrice as $p) {
                    $reply .= "• [{$p->name}](".route('detail', $p->id).'): Rp '.number_format($p->weekday_price, 0, ',', '.').' (Wd) / Rp '.number_format($p->weekend_price ?? $p->weekday_price, 0, ',', '.')." (We)\n";
                }
            }

            $reply .= "\n*Catatan: Tarif parkir dan wahana tambahan dapat bervariasi sesuai ketentuan pengelola.*";

            return [
                'reply' => $reply,
                'suggestions' => ['Cek Cuaca Hari Ini', 'Lanakila Lake', 'Taman Tirta Asri', 'Peta Lokasi'],
            ];
        }

        // 10. Map / Navigation GIS Query
        if ($this->containsAny($lower, ['peta', 'rute', 'gis', 'arah', 'koordinat', 'lokasi', 'navigasi'])) {
            return [
                'reply' => "🗺️ **Peta Informasi Geografis (SIG) Pringsewu**:\n\n".
                    "Website ini dilengkapi peta interaktif berbasis Leaflet yang memetakan seluruh titik koordinat destinasi wisata dan sentra UMKM di Kabupaten Pringsewu.\n\n".
                    "Fitur pada Peta:\n".
                    "• Titik penanda (marker) wisata dan UMKM dengan popup detail.\n".
                    "• Batas wilayah administratif Kabupaten Pringsewu (GeoJSON).\n".
                    "• Daftar lokasi di sisi samping dengan tombol fokus otomatis ke peta.\n\n".
                    '👉 [Buka Peta Wisata & UMKM Sekarang]('.route('peta').')',
                'suggestions' => ['Buka Peta Wisata', 'Cek Cuaca Hari Ini', 'Wisata Alam'],
            ];
        }

        // 11. News / Berita Query
        if ($this->containsAny($lower, ['berita', 'artikel', 'kabar', 'acara', 'event', 'agenda', 'kegiatan', 'festival'])) {
            $latestNews = Berita::latest('published_at')->take(4)->get();

            if ($latestNews->isNotEmpty()) {
                $reply = "📰 **Kabar Berita & Publikasi Terkini Pringsewu**:\n\n";
                foreach ($latestNews as $n) {
                    $date = $n->published_at ? $n->published_at->format('d M Y') : $n->created_at->format('d M Y');
                    $reply .= "• [{$n->title}](".route('berita.detail', $n->slug).") ({$date})\n";
                }
                $reply .= "\n👉 [Baca Semua Berita & Artikel](".route('berita').')';

                return [
                    'reply' => $reply,
                    'suggestions' => ['Cek Cuaca Hari Ini', 'Baca Berita Selengkapnya', 'Wisata Populer'],
                ];
            }

            return [
                'reply' => 'Belum ada berita terbaru yang diterbitkan. Silakan cek halaman [Berita]('.route('berita').') secara berkala!',
                'suggestions' => ['Cek Cuaca Hari Ini', 'Rekomendasi Wisata', 'Peta Wisata'],
            ];
        }

        // 12. UMKM / Kuliner / Oleh-oleh Query
        if ($this->containsAny($lower, ['umkm', 'kuliner', 'makanan', 'kopi', 'tapis', 'belanja', 'oleh-oleh', 'jajanan', 'pasar'])) {
            $umkms = Destination::where('category', 'UMKM')->take(5)->get();
            $culinary = Destination::where('name', 'like', '%kuliner%')
                ->orWhere('tags', 'like', '%kuliner%')
                ->orWhere('tags', 'like', '%kopi%')
                ->take(5)
                ->get();

            $reply = "🛍️ **Rekomendasi Sentra UMKM & Kuliner Khas Pringsewu**:\n\n";

            $combined = $umkms->merge($culinary)->unique('id');

            if ($combined->isNotEmpty()) {
                foreach ($combined->take(5) as $item) {
                    $reply .= "• **[{$item->name}](".route('detail', $item->id).")** - Kec. {$item->district}\n".
                        "  ⭐ {$item->rating} · {$item->location}\n";
                }
            } else {
                $reply .= "• **Wisata Kuliner Nggruput**: Surganya kuliner tradisional setiap hari Minggu pagi di komplek Pemda Pringsewu.\n".
                    "• **Desa Wisata Kampung Tapis Lugusari**: Sentra kerajinan kain tapis khas Lampung yang mendunia.\n";
            }

            $reply .= "\n👉 Lihat daftar produk dan lokasi lengkap di [Katalog Produk & UMKM](".route('daftar-produk').').';

            return [
                'reply' => $reply,
                'suggestions' => ['Wisata Kuliner Nggruput', 'Kampung Tapis Lugusari', 'Cek Cuaca Hari Ini'],
            ];
        }

        // 13. General / Tourism Recommendations Query
        if ($this->containsAny($lower, ['wisata', 'alam', 'rekomendasi', 'liburan', 'jalan', 'kunjungan', 'populer', 'favorit', 'terbaik', 'danau', 'bukit', 'taman'])) {
            $topWisata = Destination::where('category', 'Wisata')
                ->orderByDesc('rating')
                ->take(5)
                ->get();

            $reply = "✨ **Rekomendasi Destinasi Wisata Pilihan di Pringsewu**:\n\n";
            foreach ($topWisata as $w) {
                $reply .= "• **[{$w->name}](".route('detail', $w->id).")** (⭐ {$w->rating})\n".
                    "  📍 {$w->location}, Kec. {$w->district}\n".
                    '  📝 '.Str::limit($w->description ?? 'Destinasi wisata favorit', 85)."\n\n";
            }

            $reply .= 'Ketik nama tempat di atas untuk melihat detail lengkap, atau kunjungi [Peta Interaktif]('.route('peta').')!';

            return [
                'reply' => $reply,
                'suggestions' => ['Cek Cuaca Hari Ini', 'Lanakila Lake', 'Taman Tirta Asri', 'Harga Tiket'],
            ];
        }

        // 14. Keyword Database Fallback Search
        $words = array_filter(explode(' ', $lower), fn ($w) => strlen($w) >= 3);
        $found = collect();

        foreach ($words as $word) {
            $matches = Destination::where('name', 'like', "%{$word}%")
                ->orWhere('district', 'like', "%{$word}%")
                ->orWhere('description', 'like', "%{$word}%")
                ->orWhere('location', 'like', "%{$word}%")
                ->take(3)
                ->get();
            $found = $found->merge($matches);
        }

        $found = $found->unique('id');

        if ($found->isNotEmpty()) {
            $reply = "Berdasarkan pencarian Anda tentang *\"{$rawMessage}\"*, berikut destinasi yang relevan:\n\n";
            foreach ($found->take(4) as $item) {
                $reply .= "• **[{$item->name}](".route('detail', $item->id).")** ({$item->category}) - Kec. {$item->district} (⭐ {$item->rating})\n";
            }
            $reply .= "\nApakah ada informasi spesifik mengenai tempat di atas yang ingin Anda ketahui?";

            return [
                'reply' => $reply,
                'suggestions' => ['Cek Cuaca Hari Ini', 'Tiket Masuk', 'Buka di Peta'],
            ];
        }

        // 15. Generic Helpful Fallback
        return [
            'reply' => "Saya adalah **Si Pringgo**, asisten AI Pringsewu.com. Saya dapat menjawab pertanyaan seputar:\n\n".
                "• 🌦️ **Cuaca**: Tanyakan cuaca hari ini di Pringsewu\n".
                "• 🏖️ **Wisata & UMKM**: Rekomendasi liburan, kuliner, dan sentra kerajinan\n".
                "• 🎟️ **Info Tiket & Jam Buka**: Biaya masuk dan jam operasional\n".
                "• 🚗 **Akses & Transportasi**: Rute menuju Pringsewu dari Bandar Lampung atau Bandara\n".
                "• 📜 **Profil & Sejarah Pringsewu**: Asal-usul nama Bambu Seribu dan profil daerah\n\n".
                'Silakan coba tanyakan hal-hal di atas atau pilih pertanyaan cepat di bawah ini!',
            'suggestions' => ['Cek Cuaca Pringsewu', 'Sejarah Pringsewu', 'Rekomendasi Wisata', 'Sentra Kuliner & UMKM'],
        ];
    }

    /**
     * Get raw real-time weather data array from Open-Meteo with caching.
     *
     * @return array{temp: float, feels_like: float, humidity: int, wind_speed: float, condition_text: string, icon: string, is_raining: bool}|null
     */
    protected function getRawWeatherData(): ?array
    {
        try {
            $data = Cache::remember('weather_pringsewu_raw', 900, function () {
                $response = Http::timeout(3)->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude' => -5.3582,
                    'longitude' => 104.9754,
                    'current' => 'temperature_2m,relative_humidity_2m,apparent_temperature,precipitation,weather_code,wind_speed_10m',
                    'timezone' => 'Asia/Jakarta',
                ]);

                return $response->successful() ? $response->json() : null;
            });

            if ($data && isset($data['current'])) {
                $current = $data['current'];
                $code = (int) $current['weather_code'];
                $weatherDesc = $this->interpretWeatherCode($code);
                $isRaining = ($current['precipitation'] > 0 || in_array($code, [51, 53, 55, 61, 63, 65, 80, 81, 82, 95]));

                return [
                    'temp' => round((float) $current['temperature_2m'], 1),
                    'feels_like' => round((float) $current['apparent_temperature'], 1),
                    'humidity' => (int) $current['relative_humidity_2m'],
                    'wind_speed' => (float) $current['wind_speed_10m'],
                    'condition_text' => $weatherDesc['text'],
                    'icon' => $weatherDesc['icon'],
                    'is_raining' => $isRaining,
                ];
            }
        } catch (\Throwable $e) {
            // Silently ignore network issue
        }

        return null;
    }

    /**
     * Get formatted real-time live weather for Kabupaten Pringsewu.
     *
     * @return array{reply: string, suggestions: array<int, string>}
     */
    protected function getLiveWeather(): array
    {
        $weather = $this->getRawWeatherData();

        if ($weather) {
            $tips = $weather['is_raining']
                ? '🌧️ **Saran Wisata**: Saat ini terpantau ada potensi hujan/gerimis di Pringsewu. Disarankan membawa payung atau singgah di sentra kuliner dan destinasi indoor.'
                : '☀️ **Saran Wisata**: Cuaca terpantau sangat mendukung untuk aktivitas outdoor (seperti bukit, danau, atau taman rekreasi air)!';

            $reply = "🌤️ **Prakiraan Cuaca Real-Time di Kabupaten Pringsewu**:\n\n".
                "• **Kondisi**: {$weather['condition_text']} {$weather['icon']}\n".
                "• **Suhu Saat Ini**: **{$weather['temp']}°C** (Terasa seperti {$weather['feels_like']}°C)\n".
                "• **Kelembapan Udara**: {$weather['humidity']}%\n".
                "• **Kecepatan Angin**: {$weather['wind_speed']} km/jam\n\n".
                "{$tips}\n\n".
                'Tertarik mengunjungi destinasi yang cocok dengan cuaca hari ini?';

            return [
                'reply' => $reply,
                'suggestions' => ['Wisata Outdoor', 'Sentra Kuliner Pringsewu', 'Buka Peta Wisata'],
            ];
        }

        return [
            'reply' => "🌤️ **Informasi Cuaca Kabupaten Pringsewu**:\n\n".
                "Kabupaten Pringsewu beriklim tropis dengan suhu harian berkisar antara **25°C hingga 32°C**.\n\n".
                "• **Waktu Terbaik Wisata Outdoor**: Pagi hari (07.00 - 10.00 WIB) atau sore hari (15.30 - 18.00 WIB) untuk menikmati pemandangan matahari terbenam (sunset) yang indah di perbukitan atau danau.\n".
                '• **Siang Hari**: Cocok untuk berburu kuliner khas di pusat UMKM dan kafe-kafe lokal.',
            'suggestions' => ['Wisata Alam Terbuka', 'Wisata Kuliner Nggruput', 'Buka Peta Wisata'],
        ];
    }

    /**
     * Map WMO weather code to Indonesian text and emoji icon.
     *
     * @return array{text: string, icon: string}
     */
    protected function interpretWeatherCode(int $code): array
    {
        return match ($code) {
            0 => ['text' => 'Cerah Berawan', 'icon' => '☀️'],
            1, 2 => ['text' => 'Sebagian Berawan', 'icon' => '⛅'],
            3 => ['text' => 'Berawan Tebal', 'icon' => '☁️'],
            45, 48 => ['text' => 'Berkabut', 'icon' => '🌫️'],
            51, 53, 55 => ['text' => 'Gerimis Ringan', 'icon' => '🌦️'],
            61, 63, 65 => ['text' => 'Hujan', 'icon' => '🌧️'],
            80, 81, 82 => ['text' => 'Hujan Deras / Showers', 'icon' => '⛈️'],
            95, 96, 99 => ['text' => 'Hujan Disertai Petir', 'icon' => '⚡'],
            default => ['text' => 'Cerah Berawan', 'icon' => '🌤️'],
        };
    }

    /**
     * Call Google Gemini API enriched with live real-time weather and destination context.
     */
    protected function callGeminiApi(string $userMessage, string $apiKey): ?string
    {
        try {
            // Destinations context with clickable markdown links
            $destinations = Destination::all(['id', 'name', 'category', 'district', 'location', 'rating', 'weekday_price', 'open_hours']);
            $destinationsList = $destinations->map(function ($d) {
                $price = $d->weekday_price ? 'Rp '.number_format($d->weekday_price, 0, ',', '.') : 'Gratis';
                $url = route('detail', $d->id);

                return "- [{$d->name}]({$url}) ({$d->category}) di {$d->location}, Kec. {$d->district}. Rating: {$d->rating}. Tiket: {$price}. Jam: {$d->open_hours}.";
            })->implode("\n");

            // Live Weather data context
            $weather = $this->getRawWeatherData();
            $weatherSection = $weather
                ? "KONDISI CUACA REAL-TIME SAAT INI DI KABUPATEN PRINGSEWU:\n".
                  "- Kondisi: {$weather['condition_text']} {$weather['icon']}\n".
                  "- Suhu Aktual: {$weather['temp']}°C (Suhu Terasa: {$weather['feels_like']}°C)\n".
                  "- Kelembapan Udara: {$weather['humidity']}%\n".
                  "- Kecepatan Angin: {$weather['wind_speed']} km/jam\n".
                  '- Status Hujan: '.($weather['is_raining'] ? 'Berpotensi Hujan/Gerimis' : 'Tidak Hujan / Bagus')."\n".
                  '- Rekomendasi: '.($weather['is_raining'] ? 'Saran wisata indoor/kuliner atau bawa payung.' : 'Cuaca sangat ideal untuk wisata alam terbuka (outdoor).')
                : 'DATA CUACA: Pringsewu beriklim tropis dengan suhu 26°C - 32°C.';

            $systemPrompt = "Anda adalah 'Si Pringgo', chatbot asisten virtual cerdas resmi untuk website SIG Pringsewu.com (Kabupaten Pringsewu, Lampung).\n\n".
                "INFORMASI REAL-TIME & LIVE DATA YANG ANDA MILIKI:\n".
                "{$weatherSection}\n\n".
                "DAFTAR DESTINASI & UMKM TERDAFTAR:\n".
                "{$destinationsList}\n\n".
                "LINK PENTING SISTEM:\n".
                '- Peta SIG Interaktif: '.route('peta')."\n".
                '- Katalog Produk & UMKM: '.route('daftar-produk')."\n".
                '- Berita & Kegiatan: '.route('berita')."\n\n".
                "INSTRUKSI KHUSUS:\n".
                "1. JIKA PENGGUNA BERTANYA TENTANG CUACA:\n".
                "   Anda MEMILIKI data cuaca real-time di atas! JANGAN PERNAH MENOLAK, jangan katakan 'saya tidak punya data real-time', dan jangan suruh cek aplikasi lain. Langsung sampaikan kondisi cuaca, suhu, kelembapan, dan angin real-time di atas dengan ramah, serta berikan rekomendasi wisata yang cocok dengan cuaca tersebut!\n".
                "2. JIKA PENGGUNA BERTANYA DILUAR KONTEKS WISATA & UMKM:\n".
                "   Anda DIPERBOLEHKAN dan SANGAT DIANJURKAN menjawab pertanyaan pengguna dengan ramah, berwawasan luas, dan sopan (seperti cuaca, sejarah Pringsewu 'Bambu Seribu', transportasi dari Bandar Lampung/Bandara, profil daerah, sains, tips perjalanan, kuliner umum, maupun obrolan ramah).\n".
                "3. FORMAT OUTPUT:\n".
                '   Gunakan bahasa Indonesia yang santun dan akrab. Gunakan bullet points bila perlu. Bila menyebut nama destinasi, gunakan link markdown yang telah diberikan di atas.';

            $response = Http::timeout(8)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $systemPrompt."\n\nPertanyaan Pengguna: ".$userMessage],
                        ],
                    ],
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            }
        } catch (\Throwable $e) {
            // Silently fall back to local knowledge engine
        }

        return null;
    }

    /**
     * Check if a string contains any of the given keywords.
     *
     * @param  array<int, string>  $keywords
     */
    protected function containsAny(string $haystack, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (str_contains($haystack, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Default quick prompt suggestions.
     *
     * @return array<int, string>
     */
    protected function getDefaultSuggestions(string $lower): array
    {
        if (str_contains($lower, 'wisata')) {
            return ['Harga Tiket', 'Peta Lokasi', 'Wisata di Gadingrejo', 'Sentra Kuliner'];
        }

        if (str_contains($lower, 'cuaca')) {
            return ['Wisata Outdoor', 'Sentra Kuliner Pringsewu', 'Buka Peta Wisata'];
        }

        return ['Cek Cuaca Pringsewu', 'Rekomendasi Wisata Populer', 'Sentra UMKM & Kuliner', 'Peta Destinasi'];
    }
}
