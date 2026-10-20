# Konteks Proyek — Ruang Budaya Palopo

> Dokumen ini disiapkan agar pekerjaan bisa dilanjutkan oleh akun/model lain. Baca dokumen ini, lalu periksa kode dan kondisi runtime terbaru sebelum mengubah apa pun. Kode proyek adalah sumber kebenaran untuk keadaan saat ini; riwayat chat sebelumnya tidak ikut terbawa otomatis.

## Tujuan

Membangun portal informasi festival dan event budaya Kota Palopo, dengan portal publik, dashboard Admin Penyelenggara, dan dashboard Superadmin. UI harus mengikuti proposal gambar semaksimal mungkin. Pemilik meminta kode yang mudah dirawat: modular, tanggung jawab jelas, DRY bila masuk akal, file/class tidak membengkak, tanpa abstraksi atau pewarisan yang dipaksakan.

## Teknologi dan kondisi versi

- Laravel 10, PHP minimal 8.1, Filament 3.3, MySQL 8+, Livewire dari Filament, dan Vite.
- Catatan penting: permintaan awal menyebut Laravel/Filament/MySQL terbaru, tetapi composer.json saat ini masih mengunci Laravel ^10.10 dan Filament ^3.3. Jangan mengklaim proyek sudah memakai versi terbaru. Upgrade major perlu dinilai terpisah karena kompatibilitas PHP dan paket.
- Untuk Composer di Windows, pernah gagal karena ekstensi PHP CLI intl tidak aktif (C:\php\php.ini). Aktifkan extension=intl di php.ini yang digunakan CLI lalu cek php --ini dan php -m. --ignore-platform-req=ext-intl bukan solusi permanen.
- Jangan membaca atau menyalin rahasia dari .env ke dokumentasi/chat. Gunakan .env.example sebagai daftar konfigurasi aman.

## Proposal desain

File gambar proposal ada di D:\joki\_proposal_media\ (image1.jpeg, image1.png s.d. image47.png, dan contact.png). Gambar acuan yang paling sering dibahas:

| Gambar | Halaman |
| --- | --- |
| 24 | Login Superadmin |
| 25 | Dashboard Superadmin, termasuk pengajuan terbaru |
| 26 | Verifikasi pengajuan |
| 27 | Festival & Event |
| 28 | Instansi |
| 29 | Kategori |
| 30 | Berita |
| 31 | Galeri |
| 32 | Lokasi |
| 33 | Notifikasi |
| 34 | Laporan |
| 35 | Login Admin Penyelenggara |

Periksa gambar langsung sebelum perubahan visual karena detail proposal harus diverifikasi, bukan ditebak. Pembicaraan terdahulu menyelaraskan header, toolbar, tabel, breadcrumb, sidebar, dan halaman admin dengan acuan-acuan di atas. Login Superadmin dan Admin Penyelenggara memang dua desain berbeda.

## Ringkasan proposal lengkap (sumber kebutuhan)

- Sumber: `D:\joki\2104411336 ANDI MUHAMMAD ALIF GUNAWAN.docx` (proposal tahun 2026). File DOCX berada satu tingkat di atas folder aplikasi; gambar acuan diekstrak ke `D:\joki\_proposal_media\`. Baca proposal penuh atau gambar terkait jika detail belum tercakup di ringkasan ini. Ringkasan ini untuk orientasi agent, bukan pengganti sumber.
- **Tujuan/masalah:** informasi jadwal, lokasi, deskripsi, perubahan jadwal, dan dokumentasi festival/event budaya Palopo tersebar. Sistem dimaksudkan menyatukan pengelolaan dan penyebaran informasi kepada masyarakat/wisatawan secara cepat, terpusat, mudah diakses dan responsif di berbagai perangkat; mendukung promosi budaya/pariwisata dan pelestarian budaya lokal.
- **Batasan:** lingkup inti adalah pengelolaan data festival/event, pengajuan oleh Admin Instansi, verifikasi oleh Super Admin, lalu publikasi informasi kepada masyarakat. Proposal tidak menuntut pengujian performa atau keamanan menyeluruh maupun detail operasional deployment.
- **Aktor dan alur utama:**
  - Super Admin/Dinas Pariwisata: login; mengelola event, penyelenggara, kategori, berita, galeri, lokasi, notifikasi, pengumuman dan laporan; memeriksa detail pengajuan/dokumen lalu menyetujui atau menolak.
  - Admin Instansi/Penyelenggara: registrasi dan login; mengelola profil; mengajukan event dengan kategori/jenis, tema, penyelenggara, deskripsi, tujuan, manfaat, lokasi, jadwal dan dokumen; memantau status (menunggu/pending, disetujui, ditolak) dan notifikasi.
  - Masyarakat/pengunjung: tanpa login; mengakses beranda, daftar dan detail event, kalender, berita, galeri, pengumuman, serta informasi sistem/kontak. Proposal menguraikan pencarian/filter/sortir untuk daftar event dan pencarian/filter kalender.
- **Cakupan halaman:** area Super Admin (login, dashboard, verifikasi, data festival/event, penyelenggara, kategori, berita, galeri, lokasi, notifikasi, laporan); area Admin (login, registrasi, dashboard, pengajuan, daftar pengajuan, profil, notifikasi); portal publik (beranda, daftar festival/event, berita, galeri, kalender event, tentang kami). Uraian halaman rinci ada di bab rancangan antarmuka; gambar di `_proposal_media` menjadi rujukan visual.
- **Informasi publik:** beranda berfokus pada event yang *akan maupun sedang diselenggarakan*, event terdekat, kategori, pengumuman terbaru, pencarian, jadwal, lokasi, dan tautan detail. Daftar festival/event menyebut *seluruh informasi ... yang tersedia* dan kartu berisi nama, kategori, tanggal, lokasi, detail plus pencarian, filter kategori/tanggal/lokasi, dan pengurutan. Proposal **tidak menetapkan dengan tegas** apakah event yang sudah berakhir harus tetap ada di daftar publik atau diarsipkan; frasa “seluruh informasi” tidak cukup untuk menyimpulkan perilaku arsip. Filter tanggal dalam kode saat ini adalah keputusan implementasi, bukan aturan eksplisit proposal (lihat `CulturalEvent::scopePublished()` dan `PortalController`). Bila kebutuhan ini muncul, putuskan eksplisit apakah daftar publik hanya menampilkan upcoming/ongoing atau menyediakan arsip; jangan menganggap persetujuan saja membuat event lampau tampil.
- **Metode dan teknologi menurut proposal:** Waterfall; perancangan UML (Use Case, Activity, Sequence, Class Diagram) dan UI di Figma; implementasi Laravel, MySQL dan PWA; pengujian fungsional dengan Black Box Testing. Rumusan masalah juga menyebut UAT, sehingga ada ketidakselarasan internal: ruang lingkup pengujian menyebut Black Box, sementara rumusan masalah menyebut Black Box dan UAT. Konfirmasi kepada pemilik sebelum menjanjikan UAT sebagai deliverable.
- **Rancangan data konseptual:** entitas/tabel yang ditampilkan adalah Super Admin, Admin, Kategori, Festival_Event, Lokasi, Dokumen, Verifikasi, Berita, Galeri, Notifikasi, dan Pengumuman. ERD/tabel proposal adalah rancangan, bukan kewajiban menyalin nama tabel atau kolom literal ke skema Laravel. Proposal juga menyebut peta/koordinat, jadwal, dokumentasi, status publikasi dan relasi. Audit kolom harus membandingkan tabel struktur database bab rancangan (sekitar halaman 75–78) dengan migration/model; jelaskan selisih dahulu, jangan ubah skema tanpa arahan.
- **PWA:** proposal mengharapkan akses lintas perangkat dan menyebut manifest, service worker, HTTPS, pemasangan aplikasi, cache/offline untuk jaringan kurang stabil. Keputusan proyek yang tercatat di bawah menyatakan tombol Pasang aplikasi dan aktivasi service worker/PWA pernah sengaja dinonaktifkan sesuai arah UI yang disepakati. Pertahankan keputusan terkini sampai pemilik meminta PWA diaktifkan kembali; bedakan dari harapan proposal.
- **Aturan yang belum pasti:** masa tayang event lampau; persetujuan registrasi instansi sebelum akun aktif (uraian proposal menyebut akun diverifikasi, implementasi saat ini mengaktifkan langsung); apakah berita/galeri/pengumuman fitur inti atau pendukung; arti status aktif/nonaktif vs publikasi; dan rincian otorisasi aksi. Jika konflik proposal dengan kode/keputusan pemilik, laporkan keduanya dan minta keputusan hanya jika pilihan berdampak material.
- **Proposal bukan keadaan implementasi:** proyek memiliki fitur lebih rinci/tambahan (mis. kontak, daftar instansi, autentikasi/reset password, pengguna/pengaturan) dan dapat berbeda dari rancangan ERD. Kode/migration menjelaskan perilaku aktual; proposal menjelaskan maksud awal. Saat diminta audit, sajikan matriks `kebutuhan proposal | implementasi sekarang | selisih | dampak/rekomendasi`.

## Keputusan fungsional dan arsitektur

- Panel Filament Superadmin memakai ID/path super (/super, login /super/login) dengan halaman login App\Filament\Pages\Auth\Login.
- Panel Admin Penyelenggara memakai ID/path admin (/admin, login /admin/login) dengan App\Filament\Pages\Auth\AgencyLogin. Tombol Masuk di portal publik menuju login Admin Penyelenggara.
- Konfigurasi panel bersama berada di app/Providers/Filament/Concerns/BuildsAdminPanel.php. User mengimplementasikan FilamentUser; canAccessPanel() membatasi super_admin ke panel super, agency_admin ke admin, serta mensyaratkan akun/instansi aktif.
- Akun Superadmin disimpan di database. Perintah artisan app:make-super-admin tersedia untuk membuat/mengubah akun Superadmin; cek app/Console/Commands dan implementasinya untuk cara pakai terkini. Variabel SUPER_ADMIN_* di .env adalah konfigurasi bootstrap, bukan tempat melihat password setelah akun dibuat. Password di DB di-hash dan tidak bisa dibaca kembali.
- Login menerima email atau username; kolom username ditambahkan melalui migration. Pengaturan akun memungkinkan perubahan profil/email/username/password. Jangan tampilkan fitur pengaturan portal/pengumuman sebagai bagian pengaturan akun kecuali proposal/kebutuhan memang menyebutnya.
- API wilayah Indonesia memakai EMSIFA melalui adapter/service dan route lokal /api/wilayah/{level}/{parent?}. Config ada di config/services.php; response di-cache. Level: provinsi, kabupaten/kota, kecamatan, kelurahan.
- SMTP Gmail dipakai untuk email reset password. Perlu Gmail App Password dengan 2-Step Verification; password akun Google biasa tidak digunakan. SMTP belum tentu aktif sampai pemilik mengisi .env sendiri.
- Penggunaan service eksternal/library tambahan boleh dipertimbangkan jika mengurangi logic buatan sendiri, tetapi pilih yang terawat dan sesuai kebutuhan; jangan menambah dependensi tanpa alasan.
- Pembagian tanggung jawab saat ini: Filament Resource per modul; controller/Service untuk logika domain atau integrasi yang sesuai; jangan pindahkan semua logika ke satu controller/class besar. Ikuti pola proyek yang sudah ada setelah inspeksi.

## Modul yang ada

- Portal publik: beranda, agenda festival/event, detail, kalender, berita, galeri, pengumuman, kontak, tentang, pendaftaran instansi.
- Admin Penyelenggara: akun/profil dan pengajuan acara.
- Superadmin: dashboard, verifikasi pengajuan, festival/event, instansi, kategori, berita, galeri, lokasi, notifikasi, laporan, pengguna, pengumuman.
- Service worker/PWA pernah tersedia; pemilik memutuskan tombol “Pasang aplikasi” tidak sesuai proposal. Layout tidak lagi memuat manifest atau mendaftarkan service worker. Portal menghapus registrasi/cache lama saat dibuka; file manifest dan worker masih tersimpan tetapi tidak dipakai. Jangan aktifkan kembali tanpa acuan/permintaan baru.

Periksa app/Filament/Resources, app/Filament/Pages, app/Filament/Widgets, routes, serta app/Services untuk inventaris kode terbaru. Fitur dan struktur dapat berubah sejak dokumen ini ditulis.

## Riwayat UI dan isu penting yang pernah ditangani

- Login Superadmin: proposal gambar 24. Login Admin Penyelenggara: proposal gambar 35. Pernah ada error Livewire “Multiple root elements”; pastikan setiap Blade Livewire tetap satu root element.
- **Alur lupa/reset password (diperiksa 20 Okt 2026):** kedua panel Filament mengaktifkan `passwordReset()` melalui `BuildsAdminPanel`, dan link lupa password di login membuka form email. Filament/Laravel membuat token acak, menyimpan bentuk token yang di-hash pada tabel `password_reset_tokens` dengan email dan `created_at`, lalu mengirim notifikasi reset lewat sistem Mail Laravel/SMTP yang dikonfigurasi di `.env`; URL reset bertanda tangan dan dibatasi panel. Reset memvalidasi token, hash password baru, memperbarui `remember_token`, memancarkan event `PasswordReset`, dan menghapus token setelah sukses; form meminta konfirmasi password dan operasi dibatasi laju. Masa berlaku sebelumnya 60 menit, sekarang disamakan dengan kebutuhan menjadi **15 menit** pada `config/auth.php`; throttle broker tetap 60 detik dan throttle request/reset Filament juga aktif. Email tak terdaftar saat ini menampilkan pesan Laravel `passwords.user` (“We can't find a user with that email address.”), sesuai alur yang diminta tetapi memungkinkan enumerasi alamat email. Pertimbangkan menggantinya dengan pesan generik demi privasi jika prioritas keamanan berubah. SMTP Gmail hanya bekerja jika kredensial dan sender sudah dikonfigurasi benar di `.env`; jangan membaca/menyalin rahasia tersebut. Perubahan masa berlaku belum diuji dengan pengiriman email nyata.
- Perbaikan visual sebelumnya mencakup dashboard Superadmin (aksi “Lihat semua” sejajar dengan judul pengajuan terbaru, tanpa search), toolbar festival/event, header dua kolom, kategori pada tabel verifikasi, konsistensi header/toolbar/tabel halaman admin, kolom aksi rata kanan, sidebar expanded/collapsed, padding topbar dan breadcrumb.
- Halaman login pernah diperbaiki agar input terlihat, bisa diketik normal, tombol terbaca, dan kontainer tidak berlebihan.
- **Perubahan paling baru:** komponen login Admin Penyelenggara diperkecil untuk mengurangi scroll. File resources/views/filament/pages/auth/agency-login.blade.php; tinggi kontainer, padding, logo, jarak, dan tombol diringkas serta ditambah aturan layar pendek/ponsel. php artisan view:cache berhasil dijalankan setelah perubahan. Jika scroll masih muncul pada perangkat tertentu, ukur viewport dan periksa CSS parent Filament serta tampilan aktual sebelum menyesuaikan lebih lanjut.
- Kedua halaman login diberi kedalaman visual yang halus (latar luar abu-abu terang, bayangan tipis, panel form sedikit berbeda) tanpa mengubah konsep proposal.
- Label checkbox “Ingat saya” harus tampak jelas. Fungsinya meminta Laravel mempertahankan autentikasi melalui cookie remember setelah browser ditutup/kunjungan berikutnya, sampai logout atau cookie kedaluwarsa.
- Tombol submit login menampilkan indikator loading dan disabled selama request `authenticate` agar klik berulang dicegah. Preferensi umum proyek: tombol untuk aksi asynchronous harus memberi feedback loading dan mencegah pengiriman berulang; gunakan kemampuan default Filament bila tersedia dan implementasikan eksplisit pada tombol Livewire kustom.
- **Perubahan terbaru halaman publik:** formulir daftar instansi dipecah menjadi kelompok Data Instansi, Alamat Instansi, dan Akun Admin Penyelenggara, dengan grid input yang konsisten, fokus/error state, dan layout responsif. CSS khusus ada di `public/css/forms.css`; view ada di `resources/views/auth/agency-register.blade.php`.
- **Pembaruan UI terbaru:** halaman publik `/daftar-instansi` dipadatkan untuk desktop menjadi wizard tiga tahap satu viewport agar halaman tidak memunculkan scroll vertikal. Layout desktop memakai komposisi editorial sederhana: pengantar ringkas di kiri dengan ruang kiri dan pembatas vertikal, formulir tanpa kartu/bayangan berlebihan di kanan, serta input bergaya garis bawah. Aturan ini hanya berlaku mulai lebar 821px; ponsel tetap dapat di-scroll agar input tidak terpotong. Styling berada di `public/css/forms.css`.
- Footer portal publik disusun ulang mengikuti proposal gambar 42: hak cipta di tengah, tautan kebijakan/syarat/bantuan di bawahnya, serta empat kotak ilustrasi di sisi kanan. Tombol “Pasang aplikasi” dihapus karena tidak ada pada desain; JS kini menghapus pendaftaran service worker dan cache PWA lama. Bantuan mengarah ke halaman Kontak; teks kebijakan dan syarat saat ini hanya tampilan karena halaman kebijakannya belum tersedia.
- **Pembaruan footer terbaru:** footer publik diselaraskan dengan proposal `image44.png`: hak cipta dan tautan berada di tengah, sementara ikon outline Instagram, Facebook, YouTube, dan X ada di sisi kanan. Ikon saat ini hanya tampilan karena URL akun resmi belum dikonfigurasi. Markup ada di `resources/views/layouts/portal.blade.php` dan CSS di `public/css/forms.css`. `forms.css` juga memakai parameter versi berbasis `filemtime` di layout untuk menghindari browser memakai cache CSS lama.
- **Navigasi pengunjung:** menu topbar menandai halaman aktif dengan teks lebih tegas dan garis bawah tipis sesuai pola proposal. Route detail acara tetap menyorot Festival & Event, detail berita menyorot Berita; status juga diumumkan lewat `aria-current="page"`. Markup berada di `resources/views/layouts/portal.blade.php`, styling di `public/css/forms.css`. Kedua stylesheet publik diberi parameter versi `filemtime` agar perubahan CSS langsung terambil browser.
- **Hero beranda:** mengikuti gambar proposal 42, panel kanan di dalam kartu sambutan dibuat transparan agar menyatu dengan latar hero. Label “PAL⟡PO” dan elemen garis kota dihapus. Placeholder gambar saat ini masih berupa wireframe, belum foto aktual. View: `resources/views/portal/home.blade.php`; override CSS: `public/css/forms.css`.
- Tombol submit pendaftaran menonaktifkan diri dan menunjukkan spinner/status pengiriman melalui helper kecil di `public/js/portal.js`; pola ini mengikuti preferensi feedback loading pada aksi asynchronous.
- Pendaftaran instansi sekarang memakai wizard 3 tahap (profil instansi, lokasi, akun pengelola) agar satu layar tidak terasa penuh dan pengunjung fokus pada kelompok data saat ini. Implementasi alur/validasi wilayah ada di `public/js/agency-registration.js`; konten di `resources/views/auth/agency-register.blade.php`; styling di bagian “Pendaftaran bertahap penyelenggara” pada `public/css/forms.css`. Copy menjelaskan akun dapat langsung dipakai untuk mengajukan event karena controller saat ini mengaktifkan akun/instansi saat registrasi; jangan tulis bahwa registrasi akun menunggu verifikasi.
- Pernah ada error kolom status pada query verifikasi; migration status kategori kemudian dijalankan. Ada dua migration uploader galeri (000002 dan 000003); cek keduanya sebelum migrasi ulang di database baru.

## Setup lokal yang tercatat

1. Pastikan PHP CLI dan ekstensi di README aktif, termasuk intl.
2. Siapkan .env lokal dari .env.example, set koneksi MySQL dan APP_KEY; jangan salin rahasia ke repo.
3. composer install
4. php artisan key:generate (hanya untuk instalasi lokal baru yang belum punya key)
5. php artisan migrate --seed
6. php artisan storage:link
7. Buat/pastikan akun Superadmin via php artisan app:make-super-admin sesuai prompt perintah.
8. php artisan serve

README menjelaskan setup lebih lanjut. Jangan menjalankan reset database atau perintah destruktif terhadap database berisi data.

## Cara melanjutkan pekerjaan

1. Baca file ini dan README.md; cek status proyek serta perubahan lokal sebelum edit.
2. Temukan gambar proposal terkait di D:\joki\_proposal_media\ dan bandingkan dengan UI aktual bila memungkinkan.
3. Pertahankan batas peran, validasi, dan aturan bisnis saat memperbaiki UI.
4. Buat perubahan kecil pada komponen yang bertanggung jawab; gunakan ulang pola bila memang mengurangi duplikasi tanpa abstraksi berlebihan.
5. Jelaskan file/fitur yang diubah dan keterbatasan yang belum diverifikasi. Pemilik sebelumnya meminta agar test tidak dijalankan kecuali diminta; jangan jalankan test suite tanpa instruksi. Pemeriksaan syntax/kompilasi yang langsung relevan boleh dilakukan dan dilaporkan.

## Prompt awal untuk penerus

> Baca PROJECT_CONTEXT.md dan README.md, lalu periksa kode serta perubahan lokal terbaru sebelum melanjutkan. Teruskan dari kondisi proyek sekarang dan cocokkan UI dengan gambar proposal terkait. Pertahankan struktur modular, clean code, DRY secara wajar, dan tanggung jawab yang jelas; hindari class/file besar, duplikasi, abstraksi atau inheritance yang dipaksakan. Jangan jalankan test suite kecuali saya minta. Jangan pernah menyalin rahasia dari .env ke output.

