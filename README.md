# Posyandu Sedap Malam

Sistem informasi **Posyandu Sedap Malam** (RW 11, Kelurahan Sukaluyu, Kecamatan Cibeunying Kaler, Kota Bandung). Aplikasi ini terdiri dari dua bagian:

- **Website publik** (`/`) berisi profil posyandu, alur pelayanan, jadwal imunisasi, tips kesehatan, galeri, struktur kader, FAQ, serta lokasi.
- **Panel kader** (`/login`) untuk mengelola data orang tua, anak, dan kader, mencatat penimbangan, serta melihat grafik perkembangan anak.

[![CI](https://github.com/FazryJavier/Posyandu-SedapMalam/actions/workflows/ci.yml/badge.svg)](https://github.com/FazryJavier/Posyandu-SedapMalam/actions/workflows/ci.yml)

## Teknologi

| Bagian | Teknologi |
|---|---|
| Backend | PHP 8.4, Laravel 12 |
| Database | MySQL 8 (tes otomatis memakai SQLite in-memory) |
| Website publik | Blade, Bootstrap 5, template Arsha (BootstrapMade) |
| Panel kader | Blade, AdminLTE 3 |
| Pengujian | PHPUnit 11 |
| CI | GitHub Actions |

## Prasyarat

- PHP 8.4 dengan ekstensi `mbstring`, `pdo_mysql`, `pdo_sqlite`, `xml`, `fileinfo`, `bcmath`
- Composer 2
- MySQL 8 (atau MariaDB yang kompatibel)
- Node.js 20+ hanya diperlukan bila ingin menjalankan Vite (`npm run dev`); tampilan saat ini tidak memerlukannya

## Memulai

```bash
git clone https://github.com/FazryJavier/Posyandu-SedapMalam.git
cd Posyandu-SedapMalam

composer install
cp .env.example .env
php artisan key:generate
```

Buat database kosong, lalu sesuaikan `DB_*` pada `.env` (nilai bawaan: database `sedapmalam`, pengguna `root`, tanpa kata sandi):

```sql
CREATE DATABASE sedapmalam CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

```bash
php artisan migrate
php artisan serve
```

Buka <http://127.0.0.1:8000>. Pengguna Laragon dapat menjalankan MySQL dari Laragon, membuat database `sedapmalam`, lalu menjalankan perintah di atas tanpa mengubah `.env`.

> Jangan menjalankan `migrate:fresh`, `migrate:refresh`, atau `db:wipe` pada database yang berisi data nyata karena perintah tersebut menghapus seluruh tabel.

### Akun kader pertama

Akun dibuat lewat halaman pendaftaran tersembunyi `/admin-registrasi-only`, lalu masuk di `/login` menggunakan `username` dan kata sandi yang didaftarkan. Halaman pendaftaran belum dilindungi login, jadi batasi aksesnya di lingkungan produksi.

## Konfigurasi `.env`

| Variabel | Bawaan | Keterangan |
|---|---|---|
| `APP_NAME` | `Posyandu Sedap Malam` | Nama aplikasi |
| `APP_URL` | `http://localhost` | URL dasar aplikasi |
| `APP_LOCALE` | `id` | Locale aplikasi; pesan bawaan Laravel masih memakai bahasa Inggris (fallback `en`) |
| `DB_CONNECTION` | `mysql` | Driver database |
| `DB_HOST` / `DB_PORT` | `127.0.0.1` / `3306` | Alamat server MySQL |
| `DB_DATABASE` | `sedapmalam` | Nama database |
| `DB_USERNAME` / `DB_PASSWORD` | `root` / kosong | Kredensial database; ganti di produksi |
| `SESSION_DRIVER` | `file` | Penyimpanan sesi |
| `CACHE_STORE` | `file` | Penyimpanan cache |
| `QUEUE_CONNECTION` | `sync` | Antrean dijalankan langsung |

Jangan menyimpan `.env` di Git. Gunakan `.env.example` sebagai acuan.

## Struktur database

Seluruh tabel dan kolom memakai bahasa Inggris. Relasi: satu `guardian` memiliki banyak `children`, dan satu `child` memiliki banyak `growth_records`.

| Tabel | Kolom utama |
|---|---|
| `users` (akun kader untuk login) | `id`, `name`, `position`, `address`, `phone`, `username` (unik), `password` |
| `guardians` (orang tua) | `id`, `father_name`, `mother_name`, `address`, `phone` |
| `children` (anak) | `nik` (PK), `name`, `age`, `weight`, `height`, `head_circumference`, `arm_circumference`, `bmi`, `guardian_id` (FK ke `guardians`, cascade) |
| `cadres` (data kader) | `id`, `name`, `position`, `address`, `phone` |
| `growth_records` (riwayat pertumbuhan) | `id`, `child_nik` (FK ke `children.nik`, cascade), `weight`, `height`, `bmi` |

Model Eloquent: `User`, `Guardian`, `Child`, `Cadre`, `GrowthRecord`.

## Rute

| URL | Akses | Fungsi |
|---|---|---|
| `/` | Publik | Website profil posyandu |
| `/login`, `/logout` | Publik | Masuk dan keluar |
| `/admin-registrasi-only` | Publik (tersembunyi) | Pendaftaran akun kader |
| `/dashboard` | Login | Ringkasan jumlah orang tua, anak, dan kader |
| `/data-orangtua`, `/data-anak`, `/data-kader` | Login | CRUD data orang tua, anak, dan kader |
| `/timbang-anak` | Login | Halaman penimbangan (masih dalam pengembangan) |
| `/grafik-perkembangan` | Login | Grafik perkembangan (masih dalam pengembangan) |

URL tetap berbahasa Indonesia agar mudah dikenali kader; nama controller, model, dan kolom database memakai bahasa Inggris.

## Mengubah isi website publik

Seluruh teks halaman `/` dibaca dari [`config/posyandu.php`](config/posyandu.php): nama, tagline, alamat, jadwal, kontak, visi dan misi, layanan, jadwal imunisasi, tips, FAQ, dan organigram kader. Nilai `null` pada `telepon`, `whatsapp`, `email`, dan `jadwal` akan menyembunyikan bagian terkait atau menampilkan teks pengganti. Foto galeri berada di `public/assets/img/Fotos/`.

Bagian statistik di website mengambil jumlah anak, orang tua, dan kader dari database, dan otomatis disembunyikan bila database tidak dapat dijangkau.

## Menjalankan tes

```bash
php artisan test
```

Tes memakai SQLite in-memory (lihat `phpunit.xml`), sehingga tidak memerlukan MySQL dan tidak menyentuh database lokal. Cakupan: halaman publik, struktur skema bahasa Inggris, pendaftaran dan login, serta CRUD orang tua, anak, dan kader.

Pemeriksaan keamanan dependensi:

```bash
composer audit
```

## Continuous Integration

GitHub Actions ([`.github/workflows/ci.yml`](.github/workflows/ci.yml)) berjalan pada setiap pull request ke `main` dan push ke `main`: instalasi dependensi, `composer audit`, lalu `php artisan test` di PHP 8.4.

## Alur kontribusi

1. Buat branch dari `main`, misalnya `feat/nama-fitur` atau `fix/nama-perbaikan`. Jangan commit langsung ke `main`.
2. Gunakan pesan commit [Conventional Commits](https://www.conventionalcommits.org/) (`feat(scope): ringkasan`).
3. Pastikan `php artisan test` lulus, lalu buka pull request ke `main` dengan deskripsi perubahan dan hasil pengujian.

## Kredit

- Template website publik: [Arsha](https://bootstrapmade.com/arsha-free-bootstrap-html-template-corporate/) oleh BootstrapMade.
- Panel kader: [AdminLTE](https://adminlte.io/).
