# Ruang Budaya Palopo

Portal informasi festival dan event budaya Kota Palopo. Aplikasi ini menggunakan Laravel, Filament untuk dashboard pengelolaan, MySQL, dan Progressive Web App untuk akses yang nyaman dari ponsel.

## Kebutuhan

- PHP 8.1 atau lebih baru dengan ekstensi `bcmath`, `ctype`, `fileinfo`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `curl`, `gd`, `intl`, dan `zip` aktif.
- Composer 2, MySQL 8.0 atau lebih baru, serta Node.js/npm hanya jika ingin membangun aset frontend sendiri.

Versi paket dipilih agar cocok dengan PHP yang tersedia di workspace. Untuk memakai generasi Laravel dan Filament terbaru, naikkan PHP ke versi minimum yang diminta rilis tersebut lalu perbarui constraint Composer.

## Menjalankan di lokal

1. Salin `.env.example` ke `.env`, isi koneksi MySQL, lalu buat database `palopo_budaya`.
2. Isi `SUPER_ADMIN_NAME`, `SUPER_ADMIN_EMAIL`, dan `SUPER_ADMIN_PASSWORD` di `.env` dengan akun awal yang aman.
3. Isi `MAIL_USERNAME` dan `MAIL_FROM_ADDRESS` dengan alamat Gmail admin yang sama. Buat Google App Password (akun Google perlu verifikasi dua langkah), lalu masukkan ke `MAIL_PASSWORD`. Jangan gunakan password Google biasa dan jangan commit `.env`.
4. Jalankan:

   ```sh
   composer install
   php artisan key:generate
   php artisan migrate --seed
   php artisan storage:link
   php artisan app:make-super-admin
   php artisan serve
   ```

5. Buka `/` untuk portal publik dan `/admin` untuk dashboard. Admin instansi mendaftar dari tombol **Ajukan acara**. Reset password mengirim email melalui SMTP Gmail setelah konfigurasi SMTP diisi.

## Modul

- **Publik:** beranda, agenda festival/event, detail acara, kalender, berita, galeri, dan halaman informasi.
- **Admin instansi:** mendaftar, masuk ke dashboard, membuat dan mengubah pengajuan acara, lalu melihat status dan catatan penolakan.
- **Super Admin:** mengelola pengajuan acara, kategori, instansi, akun pengelola, berita, galeri, dan pengumuman. Acara hanya tampil setelah disetujui.
- **Wilayah:** dropdown provinsi, kabupaten/kota, kecamatan, dan kelurahan memakai EMSIFA API. Response disimpan dalam cache tujuh hari. Jika layanan gagal, dropdown mengabarkan kegagalan dan tidak menampilkan data palsu.
- **Cache lama:** implementasi service worker/PWA sebelumnya tidak lagi didaftarkan karena tombol pemasangan tidak ada pada desain proposal. Browser yang pernah mendaftarkannya akan diminta menghapus worker dan cache lama saat portal dibuka.

## Struktur aplikasi

Model dan relasi Eloquent mewakili entitas bisnis. `PortalController` menyiapkan data tampilan publik, `IndonesiaRegionService` menjadi adapter untuk API wilayah, dan transaksi database menjaga pembuatan instansi beserta akun tetap atomik. Setiap modul pengelolaan memiliki Filament Resource tersendiri; otorisasi peran dibatasi pada panel dan resource terkait. Konfigurasi database, email, URL, dan akun awal berada di environment.
