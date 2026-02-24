# Medius - Full-Stack Medium Clone ✍️

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-2D3441?style=for-the-badge&logo=alpine.js&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-316192?style=for-the-badge&logo=postgresql&logoColor=white)

Medius adalah aplikasi web platform *blogging* *full-stack* yang terinspirasi dari antarmuka dan pengalaman pengguna (UX) Medium. Proyek ini dibangun dengan arsitektur monolith menggunakan ekosistem Laravel modern untuk mendemonstrasikan kemampuan pengembangan backend yang solid dipadukan dengan interaktivitas frontend yang responsif.

> **Live Demo:** https://mangix.my.id/

---

## ✨ Fitur Utama

Aplikasi ini tidak hanya mencakup operasi CRUD standar, tetapi juga fitur interaksi komunitas yang kompleks:

* **Pengalaman Menulis Modern (WYSIWYG):** Terintegrasi dengan **Trix Editor** untuk penulisan artikel bergaya *rich-text* yang bersih dan aman.
* **Sistem Manajemen Gambar:** Menggunakan **Spatie Media Library** untuk menangani *upload* dan penyimpanan *cover* artikel secara efisien.
* **Komentar Berantai (*Threaded Comments*):** Pengguna dapat mengomentari artikel dan saling membalas komentar. UI dibuat reaktif tanpa *reload* halaman menggunakan **Alpine.js**.
* **Otorisasi Ketat:** Implementasi Laravel Policies untuk memastikan pengguna hanya dapat mengedit atau menghapus komentar/postingan milik mereka sendiri.
* **Feed yang Dipersonalisasi:** Navigasi topik *horizontal-scrolling* dinamis. Pengguna dapat mem-follow/unfollow kategori, dan *feed* utama akan menyesuaikan dengan preferensi mereka.
* **Sistem Bookmark (Daftar Bacaan):** Pengguna dapat menyimpan artikel favorit mereka untuk dibaca nanti dengan akses cepat melalui menu navigasi.
* **Notifikasi Dalam Aplikasi (*In-App Notifications*):** Sistem notifikasi *real-time-like* dengan indikator lonceng ketika pengguna mendapatkan pengikut baru atau balasan komentar.
* **Pencarian Cerdas:** Terintegrasi dengan **Laravel Scout** (Driver Database) untuk mencari artikel berdasarkan judul, mencari pengguna, atau memfilter artikel berdasarkan hashtag kategori (contoh: `#laravel`).

---

## 🛠️ Teknologi yang Digunakan

* **Backend:** Laravel 11 (PHP 8.2+)
* **Frontend:** Blade Templates, Tailwind CSS, Alpine.js
* **Database:** PostgreSQL (Production) / MySQL/SQLite (Local)
* **Paket Utama:**
  * `spatie/laravel-medialibrary` (Manajemen file)
  * `laravel/scout` (Pencarian teks penuh)
  * `laravel/breeze` (Otentikasi *starter kit*)

---

## 📸 Tangkapan Layar (*Screenshots*)

*(Ganti link gambar di bawah ini dengan screenshot aplikasi Anda yang sebenarnya)*

| Halaman Beranda & Feed | Halaman Detail & Komentar |
| :---: | :---: |
| <img src="link_gambar_homepage_anda_disini.jpg" width="400" alt="Homepage Screenshot"> | <img src="link_gambar_detail_anda_disini.jpg" width="400" alt="Detail Screenshot"> |

---

## 🚀 Panduan Instalasi (Lokal)

Jika Anda ingin menjalankan proyek ini di mesin lokal Anda, ikuti langkah-langkah berikut:

**1. Clone repositori**
```bash
git clone [https://github.com/Solakhuddin/medius.git](https://github.com/Solakhuddin/medius.git)
cd nama-repo-anda
```
##2. Instal dependensi PHP dan Node.js**
```bash
composer install
npm install
```
##3. Siapkan file konfigurasi**
Duplikat file .env.example menjadi .env.
```bash
cp .env.example .env
```
##4. Generate Application Key**
```bash
php artisan key:generate
```
##5. Konfigurasi Database**
Buka file .env dan sesuaikan kredensial database Anda (misalnya menggunakan MySQL lokal):
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_lokal_anda
DB_USERNAME=root
DB_PASSWORD=
```
##6. Jalankan Migrasi database**
```bash
php artisan migrate
```
##7. Buat Symlink untuk penyimpanan (Wajib untuk Spatie Media Library)**
```bash
php artisan storage:link
```
##8. Build aset frontend dan jalankan server lokal**
Jalankan kedua perintah ini di terminal yang terpisah:
```bash
npm run dev
```
```bash
php artisan serve
```
