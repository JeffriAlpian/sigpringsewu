# 🌿 Pringsewu.com

Portal informasi wisata dan UMKM Kabupaten Pringsewu berbasis web, dibangun dengan Laravel 13 dan Tailwind CSS. Dilengkapi peta interaktif, chatbot AI asisten (*Si Pringgo*), manajemen konten berita, serta autentikasi Google OAuth.

---

## ✨ Fitur Utama

| Fitur | Keterangan |
|---|---|
| 🗺️ Peta Interaktif | Visualisasi lokasi destinasi wisata & UMKM di peta |
| 🏖️ Daftar Destinasi | Listing destinasi dengan kategori, harga, rating & jam buka |
| 📰 Berita | Artikel berita seputar Pringsewu dengan slug URL |
| 🤖 Chatbot *Si Pringgo* | Asisten virtual berbasis Gemini AI atau knowledge-engine lokal |
| 🔐 Autentikasi | Login/register manual + Google OAuth via Socialite |
| 🛠️ Admin Panel | CRUD destinasi, berita, dan pengguna untuk role `admin` |

---

## 🛠️ Teknologi

- **Backend:** Laravel 13 (PHP 8.3)
- **Frontend:** Blade + Tailwind CSS v4 + Vite
- **Database:** SQLite (default) atau MySQL
- **Autentikasi:** Laravel Breeze + Socialite (Google)
- **AI Chatbot:** Gemini API (opsional) + knowledge-engine lokal

---

## ⚡ Instalasi Cepat

### Prasyarat

- PHP >= 8.3
- Composer
- Node.js & npm

### Setup

```bash
# 1. Clone repositori
git clone <repo-url> pringsewubisa
cd pringsewubisa

# 2. Salin konfigurasi lingkungan
cp .env.example .env

# 3. Install semua dependensi & build (satu perintah)
composer run setup
```

> Perintah `composer run setup` akan: install dependensi Composer, generate `APP_KEY`, jalankan migrasi, install npm, dan build asset.

### Jalankan Server

```bash
composer run dev
```

Akses aplikasi di **http://localhost:8000**

---

## ⚙️ Konfigurasi Environment

Salin `.env.example` ke `.env` dan sesuaikan nilai berikut:

```dotenv
APP_NAME=Pringsewu.com
APP_URL=http://localhost:8000

# Database (default SQLite)
DB_CONNECTION=sqlite

# Chatbot AI (opsional — tanpa ini, gunakan knowledge-engine lokal)
GEMINI_API_KEY=your_gemini_api_key

# Google OAuth (opsional)
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

---

## 🗄️ Database & Seeding

```bash
# Jalankan migrasi
php artisan migrate

# Isi data awal (destinasi + user default)
php artisan db:seed
```

### Akun Default

| Role | Email | Password |
|---|---|---|
| Admin | `admin@pringsewu.com` | `admin123` |
| User | `user@pringsewu.com` | `user123` |

---

## 🧭 Struktur Halaman

| URL | Halaman |
|---|---|
| `/` | Beranda |
| `/peta` | Peta interaktif destinasi |
| `/daftar-produk` | Daftar destinasi & UMKM |
| `/detail/{id}` | Detail destinasi |
| `/berita` | Daftar berita |
| `/berita/{slug}` | Detail artikel berita |
| `/login` | Login / Register |
| `/dashboard` | Dashboard pengguna *(auth)* |
| `/admin/dashboard` | Dashboard admin *(admin only)* |
| `/admin/destinations` | Kelola destinasi *(admin only)* |
| `/admin/berita` | Kelola berita *(admin only)* |
| `/admin/users` | Kelola pengguna *(admin only)* |

---

## 🤖 Chatbot *Si Pringgo*

Chatbot tersedia di seluruh halaman publik. Ada dua mode:

1. **Mode AI (Gemini)** — Aktif bila `GEMINI_API_KEY` dikonfigurasi di `.env`. Jawaban lebih dinamis dan kontekstual.
2. **Mode Knowledge-Engine Lokal** — Aktif otomatis tanpa API key. Menjawab pertanyaan seputar destinasi, UMKM, cuaca, dan info Pringsewu berdasarkan data database.

---

## 🧪 Testing

```bash
# Jalankan seluruh test suite
php artisan test --compact

# Atau langsung via Pest
vendor/bin/pest
```

---

## 📁 Struktur Direktori Penting

```
app/
├── Http/Controllers/
│   ├── Admin/          # Controller admin (Destinations, Berita, Users)
│   ├── Auth/           # AuthController, SocialiteController
│   ├── BeritaController.php
│   └── ChatbotController.php
├── Models/
│   ├── Destination.php
│   ├── Berita.php
│   └── User.php
database/
├── migrations/         # Skema tabel
├── seeders/            # Data awal destinasi & user
└── data/
    └── destinations.json  # Dataset destinasi Pringsewu
resources/views/
├── layouts/            # Layout utama (app, admin)
├── pages/              # Halaman publik
├── admin/              # Panel admin
└── components/         # Komponen (chatbot, destination-card)
```

---

## 📄 Lisensi

Proyek ini bersifat open-source di bawah lisensi [MIT](https://opensource.org/licenses/MIT).
