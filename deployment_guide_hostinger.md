# 🚀 Panduan Deployment ke Hostinger (PARTI 2026 Himatif UMS)

Dokumen ini berisi panduan langkah demi langkah untuk mengunggah dan mengonfigurasi proyek **PARTI 2026** di **Hostinger Shared Hosting** menggunakan arsitektur **Flat-Root Deployment** (file `index.php` dan `.htaccess` berada langsung di dalam folder `public_html/`).

---

## 📋 Ringkasan Arsitektur Hosting

| Komponen | Spesifikasi |
|---|---|
| **Web Server** | LiteSpeed / Apache (Hostinger Shared Hosting) |
| **PHP Version** | **PHP 8.3** (atau 8.2+) |
| **Database** | MySQL / MariaDB Hostinger |
| **Arsitektur Deploy** | Flat-Root (`public_html/` sebagai root Laravel langsung) |
| **Storage Disk** | `public` (dengan fallback otomatis via route Laravel `/storage/*`) |
| **Sesi & Cache** | Driver `file` (paling stabil & hemat memori untuk shared hosting) |

---

## 🛠️ Langkah 1: Persiapan Database di Hostinger

1. Masuk ke panel kontrol Hostinger (**hPanel**).
2. Buka menu **Databases** -> **Management**.
3. Buat database baru:
   * **Database Name**: Sesuaikan dengan `.env` (contoh: `u324744819_PartiUms` atau nama baru pilihan Anda).
   * **Username**: Sesuaikan dengan `.env` (contoh: `u324744819_Partiums`).
   * **Password**: Tentukan password yang kuat.
4. Buka **phpMyAdmin** untuk database yang baru dibuat.
5. Klik tab **Import**, lalu pilih file:
   `database/parti2026_database.sql`
6. Klik tombol **Import** (atau **Go**) di bagian bawah. Tunggu hingga 14 tabel berhasil dibuat.

---

## 📦 Langkah 2: Mengunggah Berkas Proyek

Ada 2 cara upload ke folder `public_html/` di Hostinger:

### Metode A: Via File Manager (Kompres ZIP - Sangat Direkomendasikan)
1. Di komputer lokal Anda, kompres semua berkas di dalam folder `parti2026_hostinger_ready` ke dalam sebuah file `.zip` (misal: `parti2026_deploy.zip`).
   
   > [!IMPORTANT]
   > **Berkas yang TIDAK PERLU diikutsertakan ke dalam ZIP:**
   > - `node_modules/` (jika ada)
   > - `.git/` atau `.github/`
   > - `tests/`
   > - `database/database.sqlite` (karena production memakai MySQL)

2. Buka **File Manager** di hPanel Hostinger.
3. Masuk ke direktori domain Anda: `public_html/`
4. Unggah file `parti2026_deploy.zip`.
5. Klik kanan file zip tersebut -> pilih **Extract** langsung di dalam `public_html/`.
6. Hapus file `.zip` setelah ekstraksi selesai.

### Metode B: Via FTP / Git
Jika menggunakan Git atau FTP (FileZilla), upload seluruh file dan folder langsung ke target direktori web.

---

## ⚙️ Langkah 3: Penyesuaian Berkas `.env` di Server

Buka file `.env` di File Manager Hostinger dan pastikan parameter berikut sesuai dengan server Anda:

```env
APP_NAME="PARTI Himatif UMS"
APP_ENV=production
APP_KEY=base64:MYzhBpze2ksZOWHOq3j6W0cMUM1JMyQXxnrHNt0AJRM=
APP_DEBUG=false
APP_URL=https://partiums.himatifums.org

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=NAMA_DATABASE_HOSTINGER_ANDA
DB_USERNAME=NAMA_USER_DATABASE_ANDA
DB_PASSWORD=PASSWORD_DATABASE_ANDA

SESSION_DRIVER=file
CACHE_STORE=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
```

> [!CAUTION]
> Pastikan `APP_DEBUG=false` agar informasi debug atau kredensial internal tidak bocor saat terjadi kesalahan teknis.

---

## 🔒 Langkah 4: Pembuatan Storage Symlink & Bersihkan Cache

Karena pada Hostinger Shared Hosting akses SSH terminal sering kali terbatas, sistem telah dilengkapi dengan **Rute Pemeliharaan Khusus Admin**:

1. Buka browser dan login ke panel admin:
   `https://domain-anda.com/login`
   * **Email Default**: `admin@parti2026.com`
   * **Password Default**: `PartiHimatif2026#SecureAdmin!`
2. Anda akan otomatis diarahkan untuk mengganti password pada login pertama demi keamanan.
3. Setelah login sebagai Superadmin, Anda dapat memicu pembuatan storage symlink dan pembersihan cache langsung melalui browser:
   * **Buat Storage Link**: Lakukan request POST ke `/admin/create-symlink` atau klik tombol maintenance di dashboard admin.
   * **Clear Cache**: Lakukan request POST ke `/admin/clear-cache`.

> [!TIP]
> Web ini juga sudah dilengkapi **Media Route Fallback** di `routes/web.php` (`/storage/{path}`). Jadi, bahkan jika symlink belum dibuat, gambar dan logo sponsor tetap tampil dengan aman dan terproteksi dari path-traversal!

---

## 🛡️ Langkah 5: Verifikasi Akhir (Post-Deploy Checklist)

- [ ] Buka halaman utama: `https://domain-anda.com/` (Periksa loading, tema dark/light, dan timeline).
- [ ] Buka halaman Tentang: `https://domain-anda.com/tentang`.
- [ ] Buka halaman FAQ: `https://domain-anda.com/faq` (Coba pencarian live dan accordion).
- [ ] Buka sitemap: `https://domain-anda.com/sitemap.xml` dan `https://domain-anda.com/robots.txt`.
- [ ] Buka panel admin: `https://domain-anda.com/admin` dan uji upload logo sponsor atau poster sub-event.
