# Dokumen Perencanaan: Penyempurnaan Error Handling PARTI 2026

Dokumen ini memuat rencana kerja teknis untuk menyempurnakan penanganan kesalahan (*error handling*) pada platform PARTI 2026, memastikan sistem tahan banting (*fault-tolerant*), menjaga keamanan data produksi, dan memberikan pengalaman pengguna yang elegan saat terjadi galat.

---

## 🎯 Tujuan Utama
1. **Keamanan Produksi**: Mencegah kebocoran struktur data, kode sumber, atau kredensial database saat terjadi kegagalan server.
2. **Kenyamanan Pengguna (UX)**: Menyajikan halaman galat (404, 403, 419, 500) yang informatif dan selaras dengan estetika visual PARTI 2026.
3. **Integritas Penyimpanan (Zero Orphan Files)**: Memastikan tidak ada berkas sampah yang tertinggal di storage hosting saat operasi database gagal.
4. **Resiliensi Sesi**: Menangani kasus *CSRF Token Expired* (Error 419) secara ramah tanpa membuat panik pengguna.

---

## 📋 Tahapan Pelaksanaan

### Fase 1: Pembuatan Halaman Galat Kustom (Custom Error Views)
Membuat template error mandiri berdesain premium di folder `resources/views/errors/`:

| Kode HTTP | Nama Galat | Deskripsi & Tindakan untuk Pengguna |
| :--- | :--- | :--- |
| **404** | Halaman Tidak Ditemukan | Tautan salah, sub-acara berstatus draft, atau event belum dipublikasikan. Menyediakan tombol kembali ke Beranda dan Jelajahi Lomba. |
| **403** | Akses Ditolak | Pengguna/Kesekretariatan mencoba mengakses menu khusus Superadmin. Tampilkan instruksi hak akses. |
| **419** | Sesi Berakhir (CSRF Expired) | Terjadi saat form dibiarkan terlalu lama. Tampilkan tombol *"Muat Ulang Halaman"* atau *"Kembali ke Halaman Sebelumnya"* untuk memperbarui token. |
| **500** | Gangguan Server Internal | Terjadi jika koneksi database terputus. Tampilkan permohonan maaf dan informasi kontak narahubung panitia (tanpa membocorkan stack trace). |

* **Karakter Desain**:
  - Menggunakan tipografi resmi: *Cinzel* (judul) dan *Work Sans* (isi).
  - Skema warna: Latar *paper-warm* (`#FAF8F5`), aksen *ember* (`#EA580C`), dan teks *ink* (`#1C140B`).
  - Responsif di perangkat mobile dan desktop.

---

### Fase 2: Integritas Berkas & Database Transactions (Anti-Orphan Files)
Mencegah penumpukan file poster, logo, dan dokumen di hosting saat proses penyimpanan gagal:

1. **Pola Transaksional di Controller**:
   - Terapkan `DB::beginTransaction()` dan `DB::commit()` pada:
     - `SubEventController::store` & `update` (Unggah Poster)
     - `SponsorController::store` & `update` (Unggah Logo Sponsor)
     - `DocumentController::store` & `update` (Unggah Template Dokumen)
2. **Rollback File Cleanup**:
   - Jika query database melempar `Exception`, blok `catch` akan:
     - Menjalankan `DB::rollBack()`.
     - Menghapus berkas yang baru saja diunggah menggunakan `Storage::disk('public')->delete($newPath)`.
     - Mencatat log error ke `storage/logs/laravel.log`.
     - Mengembalikan redirect ke form dengan pesan galat ramah: *"Gagal menyimpan data, silakan coba lagi."*
3. **Pembersihan Berkas saat Hapus Permanen**:
   - Saat sub-acara atau dokumen dihapus, berkas terkait di storage fisik otomatis dibersihkan agar hemat kapasitas disk Hostinger.

---

### Fase 3: Penanganan Global di Laravel 11 (`bootstrap/app.php`)
Mengonfigurasi exception handler modern bawaan Laravel 11:

1. **Auto-Redirect Ramah pada Error 419 (TokenMismatchException)**:
   - Jika form web admin mengalami sesi kadaluarsa, sistem otomatis me-redirect kembali ke halaman form sebelumnya (`redirect()->back()`) dengan pesan toast peringatan:
     > *"Sesi Anda telah diperbarui karena tidak ada aktivitas. Silakan kirimkan kembali data Anda."*
2. **Penanganan Database Connection Lost di Halaman Publik**:
   - Jika database MySQL Hostinger mengalami gangguan sesaat, halaman publik tidak akan menampilkan layar crash 500, melainkan fallback ke tampilan ramah *Maintenance Mode / Layanan Sedang Diperbarui*.

---

### Fase 4: Checklist Konfigurasi Produksi Hostinger
Langkah verifikasi akhir sebelum dan sesudah rilis:

- [ ] Pastikan baris `.env` di server produksi adalah `APP_ENV=production`.
- [ ] Pastikan baris `.env` di server produksi adalah `APP_DEBUG=false`.
- [ ] Set `LOG_LEVEL=error` agar file log hosting tidak cepat membengkak.
- [ ] Uji coba akses URL acak (`/halaman-asal-coba`) untuk memastikan halaman 404 kustom tampil sempurna.
- [ ] Uji coba submit form dengan token CSRF buatan untuk memastikan penanganan 419 berjalan mulus.

---

## 🚀 Jadwal & Rekomendasi Eksekusi
Rencana ini disarankan untuk dieksekusi **setelah** fitur Buka/Tutup Pendaftaran dan perbaikan migration yang sudah siap saat ini di-push ke GitHub dan diverifikasi di server Hostinger.
