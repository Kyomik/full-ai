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
  - Super Admin/Dinas Pariwisata: login; mengelola event, penyelenggara, kategori, berita, galeri, lokasi, notifikasi dan laporan; memeriksa detail pengajuan/dokumen lalu menyetujui atau menolak. Pengumuman publik dikirim sebagai notifikasi berkategori Pengumuman.
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
- Superadmin: dashboard, verifikasi pengajuan, festival/event, instansi, kategori, berita, galeri, lokasi, notifikasi, laporan, dan pengguna. Pengumuman portal berasal dari notifikasi berkategori Pengumuman; tidak ada lagi tabel atau CRUD pengumuman terpisah.
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

## Update on 2026-10-21
- Lebar sidebar bersama untuk panel Admin Penyelenggara dan Super Admin diubah menjadi `20rem` melalui `app/Providers/Filament/Concerns/BuildsAdminPanel.php`.
- Halaman Notifikasi kini tampil di navigasi Admin Instansi dan menyediakan tab Semua/Belum Dibaca/Pengajuan/Sistem, pagination, indikator status baca, serta aksi tandai semua sebagai dibaca. Tampilan Admin Instansi mengikuti proposal `image41.png`; tampilan Super Admin tetap memakai tabel pengelolaan dan aksi kirim notifikasi.
- Perbaikan tipe return pada query notifikasi Admin: relasi `User::notifications()` bertipe `MorphMany`, bukan `EloquentBuilder`.
- Tab Notifikasi Admin disetel membungkus dan menyembunyikan overflow horizontal agar tidak menampilkan scrollbar di sisi tab Sistem.
- Komponen Logout sidebar diberi `margin-top: auto` pada container hook agar tombol Logout rata bawah sidebar.
- Dropdown wilayah pada formulir registrasi instansi kini menampilkan spinner dan teks loading per field saat mengambil provinsi, kabupaten/kota, kecamatan, atau kelurahan dari API eksternal; indikator disembunyikan setelah berhasil/gagal.
- Poster pada ringkasan tabel Daftar Pengajuan memakai URL relatif `/storage/...` agar tidak terpengaruh `APP_URL` yang berbeda dari host/port saat aplikasi dijalankan.
- Halaman Profil Penyelenggara disusun ulang mengikuti proposal `image40.png`: header/subheading, tombol Riwayat Perubahan, tab Data Penyelenggara dan Kontak Penanggung Jawab, panel informasi dua kolom, upload logo, serta tombol Simpan Perubahan.
- Preview upload profil memakai URL filesystem publik relatif (`/storage`) agar tidak macet karena host/port `APP_URL`; tombol Riwayat Perubahan kini membuka modal yang menampilkan waktu pembuatan dan pembaruan profil.
- Tombol Simpan Perubahan Profil kini memiliki state Livewire: disabled dan menampilkan indikator loading bawaan Filament selama aksi `save` berjalan.
- Spinner manual pada tombol Simpan dihapus karena komponen tombol Filament sudah menyediakan indikator loading bawaan; sekarang hanya satu spinner yang tampil.
- Logo agency yang tersimpan kini dipakai pada identitas sidebar dan avatar menu user di topbar Admin Instansi; jika belum ada logo, fallback ikon/avatar tetap digunakan.
- Lebar sidebar desktop saat mode terbuka disesuaikan menjadi `16rem` pada konfigurasi Filament dan variabel CSS topbar.
- Saat admin penyelenggara membuat pengajuan event, notifikasi database `EventSubmittedForReview` dikirim ke semua Super Admin aktif dan memakai format Filament agar dapat tampil di halaman serta dropdown notifikasi.
- Item `Pengguna Sistem` pada sidebar diturunkan mendekati Logout dengan selector item langsung; satu garis pemisah ditempatkan tepat di atasnya, tanpa garis di atas Dashboard atau Logout.
- Aksi tabel berbasis link seperti Lihat/Edit kini menampilkan spinner saat berpindah halaman; aksi Livewire seperti Hapus tetap memakai indikator loading bawaan Filament.
- Tombol header `CreateAction` seperti Tambah Instansi dan Tambah Festival/Event kini ikut menampilkan spinner saat menuju halaman create.
- Posisi spinner aksi link diseragamkan di sebelah kiri teks tombol.
- Kolom `Judul Event` pada tabel Festival & Event dipersempit menjadi sekitar 22%; komponen ringkasannya tidak lagi memaksa lebar minimum 16rem.
- Input filter tanggal pada halaman Verifikasi dan Festival/Event memanggil picker saat seluruh input diklik, bukan hanya area ikon kalender.
- Dropdown Jenis Instansi dan Status pada toolbar halaman Instansi diberi padding kiri/kanan agar teks option tidak menempel ke tepi.
- Tombol Reset Filter halaman Instansi kini memiliki state disabled, opacity, dan spinner saat proses reset berlangsung.
- Tombol Reset Filter yang berulang pada seluruh toolbar/filter panel kini memakai komponen bersama `x-resource-filter-reset`, termasuk spinner dan state disabled yang konsisten.
- Kolom tabel Instansi diberi class layout khusus agar Nama Instansi benar-benar lebar (30%/minimum 20rem) dan Email tetap ringkas (12%).
- Alignment kolom Aksi pada kedua varian tabel diseragamkan ke kiri melalui style tabel bersama, termasuk label header dan tombol aksi.
- Konfigurasi tabel yang masih memakai `actionsAlignment('end')` diubah langsung menjadi `actionsAlignment('start')` agar alignment kiri tidak tertimpa konfigurasi Filament.
- Status aktif/nonaktif distandarkan: dropdown Instansi dan Kategori hanya mengambil data aktif, Galeri hanya memilih event approved, dan scope event publik hanya menampilkan event dengan Instansi serta Kategori aktif; tabel pengelolaan Super Admin tetap menampilkan semua status.
- Padding dropdown dipindahkan ke komponen bersama `x-resource-filter-toolbar`, sehingga dropdown Status pada Kategori dan seluruh toolbar sejenis memiliki jarak teks yang konsisten tanpa aturan khusus per halaman.
- Celah portal publik ditutup: Galeri hanya menampilkan item dari event publik, dan halaman detail event menolak event yang instansinya atau kategorinya sudah nonaktif.
- `DemoContentSeeder` ditambahkan dan didaftarkan pada `DatabaseSeeder` untuk membuat data dummy berita, galeri, lokasi, serta notifikasi Super Admin; seeder memakai `updateOrCreate`/ID notifikasi deterministik agar aman dijalankan ulang.
- Isi berita pada portal publik kini mempertahankan format RichEditor melalui sanitizer allowlist `App\Support\RichText`; tag format aman ditampilkan, sedangkan atribut/event dan protokol link berbahaya dibuang.
- Validasi upload diperjelas: Galeri membatasi Foto/Video sesuai pilihan jenis, poster/logo/gambar berita hanya gambar, dan Dokumen Pendukung hanya menerima gambar, video, PDF, Word, atau Excel.
- Input rentang tanggal pada Berita, Galeri, Festival/Event, Verifikasi, dan Laporan kini memakai komponen bersama `x-resource-filter-date-range` dengan perilaku picker interaktif yang sama.
- Dropdown wilayah berbasis API pada form Festival/Event dan Lokasi kini memakai indikator loading bersama; level berikutnya menampilkan spinner sampai request Livewire/API selesai.
- Aksi `Buat & buat lainnya` pada form Festival/Event kini mereset state Wizard ke langkah pertama setelah form dikosongkan.
- Input Nama Kategori kini memvalidasi keunikan sebelum penyimpanan agar duplikasi menampilkan pesan validasi form, bukan error SQL mentah.
- Nama dan Email Instansi kini divalidasi unik sebelum penyimpanan; Email juga tetap dilindungi unique constraint di database.
- Event kini mendukung pemakaian lokasi master secara hybrid: kolom opsional location_id ditambahkan ke cultural_events, pilihan lokasi aktif dapat mengisi otomatis nama tempat, alamat, wilayah, dan koordinat, sementara seluruh field lokasi tetap bisa diedit manual.
- Alur lokasi disederhanakan: Festival/Event wajib memilih lokasi aktif dari Data Lokasi; form Data Lokasi hanya menyimpan nama, kategori, alamat, koordinat, dan status; field wilayah API dipindahkan ke form Instansi di bawah alamat.
- README diperbarui dengan langkah menjalankan scheduler notifikasi memakai schedule:work, contoh cron produksi, dan catatan Windows Task Scheduler.
- Aksi Lihat dan Unduh pada tabel Laporan kini memakai spinner loading bersama; aksi custom laporan juga dinonaktifkan sementara saat proses berjalan.
- Export laporan diubah dari CSV menjadi workbook Excel XLSX memakai maatwebsite/excel; export per domain memiliki header dan autofit kolom, sedangkan Unduh Semua Laporan memiliki sheet terpisah untuk setiap domain.
- Status laporan dan waktu pada kolom Dibuat Pada kini tersimpan per Super Admin dan periode: Belum Diunduh, Diproses, Selesai, atau Gagal; waktu diisi saat export berhasil dibuat.
- Halaman Pengguna Sistem direfaktor ke varian tabel pengelolaan bersama Instansi/Kategori/Lokasi: header, toolbar filter, reset, tabel flat Filament, alignment aksi kiri, serta filter Peran dan Status.
- Kolom No dihapus dari tabel Pengguna Sistem agar ruang kolom informasi pengguna lebih lega.
- Badge peran Pengguna Sistem dibedakan: Admin Instansi memakai warna info dan Super Admin memakai warna warning; aksi Ubah dipaksa memakai warna oranye agar tidak tampil hijau.
- Logout dari menu profil Topbar kini membuka modal konfirmasi yang sama dengan Logout pada Sidebar.
- Pesan berhasil logout kini memakai flash message pada layout portal publik sehingga langsung tampil setelah diarahkan ke Beranda, tanpa tertunda sampai halaman login admin.
- Pesan reset password disesuaikan ke Bahasa Indonesia: email tidak ditemukan, link tidak valid/kedaluwarsa, link berhasil dikirim, dan password berhasil diperbarui; masa berlaku token tetap 15 menit melalui konfigurasi broker password.
- Tombol Simpan Pengaturan Akun tidak lagi memakai spinner ganda; spinner bawaan Filament dipertahankan dan pemicu spinner CSS global dihapus.
- Tombol Ya, Keluar pada modal konfirmasi Logout kini memakai indikator loading bawaan Filament melalui event form processing, bukan spinner CSS global.
- Label field Username/Email dan Password pada login Super Admin serta Admin dipaksa tampil dengan warna dan visibilitas yang jelas melalui style masing-masing layout login.
- Link Lupa password pada kedua login diberi garis bawah, dan checkbox Ingat saya dibuat selalu terlihat dengan state checked serta focus yang jelas.
- Feature test notifikasi ditambahkan untuk memastikan broadcast langsung dari halaman Notifikasi masuk ke semua Admin Instansi aktif, jadwal tersimpan sebagai scheduled, dan command scheduler mengirimnya saat sudah jatuh tempo tanpa mengirim ke akun nonaktif.
- Warna aksi Edit/Ubah pada seluruh tabel Filament distandarkan secara global menjadi warning/oranye melalui konfigurasi `EditAction`, bukan hanya pada tabel Pengguna Sistem.
- Broadcast notifikasi kini menyimpan `data.format = filament` agar terbaca oleh komponen bell Filament, termasuk badge jumlah unread dan daftar notifikasi pada modal topbar.
- Migrasi backfill ditambahkan agar broadcast notifikasi lama yang belum memiliki `format` juga terbaca oleh bell Filament.
- Salinan broadcast pada Super Admin kini memakai format tracking terpisah sehingga tetap tercatat pada Data Notifikasi, tetapi tidak muncul sebagai notifikasi masuk di bell pengirim; hanya Admin Instansi yang menerima format bell Filament.
- Bell Admin memakai komponen custom: menekan bell menyembunyikan badge selama sesi tab; panel bell menampilkan notifikasi belum dibaca. Klik notifikasi tidak langsung mengubah database atau menghilangkan item.
- Klik notifikasi di panel bell maupun halaman Notifikasi memasukkan ID ke batch baca di session tanpa mengubah database per klik. Item bell yang dipilih tetap tampil dalam sesi aktif; halaman Notifikasi menampilkan status baca secara sementara dan aksi Lihat membuka modal detail, bukan langsung menavigasi ke data event.
- Saat Logout, batch ID notifikasi dibaca disimpan sekaligus lewat satu update sebelum sesi berakhir. Setelah login ulang, item yang dipilih tidak lagi muncul di panel bell; item yang tidak dipilih tetap belum dibaca. Semua record tetap tersedia di halaman Notifikasi.
- Override topbar Filament mendeklarasikan prop `navigation` seperti template bawaan agar `$attributes` tidak terbaca sebagai array saat merender halaman.
- Wrapper komponen notifikasi bell dipindahkan ke path komponen yang benar agar tidak terjadi recursive view rendering yang menghilangkan topbar.
- Alert logout pada portal publik diubah menjadi toast fixed di kanan atas agar tidak mendorong atau merusak struktur konten halaman.
- Filter Status pada halaman Laporan kini mengikuti status unduhan aktual: Belum Diunduh, Diproses, Selesai, dan Gagal.
- Ikon pada kolom Judul Notifikasi diratakan secara vertikal dengan isi judul agar konsisten antarbaris.
- Ikon pada kolom Jenis Laporan juga diratakan secara vertikal dengan isi laporan.
- Jarak Pengguna Sistem dan Logout pada sidebar mobile diperbaiki dengan spacing khusus agar keduanya tidak menempel tanpa menambah garis pemisah baru.
- Jarak mobile antara Pengaturan Akun dan Logout diperlebar menjadi 1rem agar tombol Logout tidak menempel pada item di atasnya.
- Aturan flex mobile sidebar diperbaiki agar Pengguna Sistem tidak terdorong sendiri ke bawah oleh margin auto; urutan Pengguna Sistem dan Logout kini mengikuti alur normal sidebar.
- Header halaman bersama diberi key berdasarkan nama route agar breadcrumb dipaksa dirender ulang saat berpindah domain melalui sidebar atau navigasi Livewire.
- Kartu statistik dashboard disetel responsif menjadi 5 kolom di desktop dan 2 kolom per baris pada tablet serta mobile.
- Ikon user fallback pada identitas sidebar Super Admin disembunyikan pada tampilan mobile; logo instansi Admin Penyelenggara tetap dipertahankan.
- Ikon user Super Admin dikembalikan pada sidebar desktop yang terbuka dan tetap disembunyikan hanya pada mobile; margin Logout disesuaikan agar lebih dekat dengan Pengguna Sistem.
- Struktur flex sidebar mobile dipulihkan agar Pengguna Sistem dan Logout tetap berada di bagian bawah; spacing khusus mobile tidak lagi mengubah layout desktop.
- Jarak mobile antara Pengguna Sistem dan Logout disamakan dengan gap item sidebar lainnya sebesar .25rem, tanpa menghilangkan posisi rata bawah.
- Tombol Simpan Perubahan pada halaman Pengaturan Akun kini memiliki state disabled dan spinner saat proses save berlangsung.
- Form Galeri tidak lagi meminta pilihan Jenis; file foto/video menerima MIME yang sesuai dan kolom type ditentukan otomatis dari MIME atau ekstensi file saat create/edit.
- Master Jenis Instansi ditambahkan melalui tabel agency_types; form Instansi memakai searchable select yang dapat memilih jenis aktif atau membuat jenis baru, dan pendaftaran publik mengambil pilihan dari master yang sama.
- Dropdown Jenis Penyelenggara pada Profil Penyelenggara juga memakai master agency_types yang sama dan dapat menambahkan opsi baru.
- Broadcast notifikasi Super Admin kini mendukung kirim sekarang atau penjadwalan; scheduler command notifications:dispatch-scheduled berjalan setiap menit, dan status tabel memakai status pengiriman Terkirim/Terjadwal/Diproses/Gagal, bukan status baca.
- Topbar Filament di-override agar memanggil komponen `App\Livewire\DatabaseNotifications` secara langsung; view notifikasi kustom memakai state `activeNotification` dan aksi `openNotification` dari kelas tersebut.
- Trigger bell pada topbar memakai wrapper flex setinggi 2.5rem dan alignment tengah agar sejajar vertikal dengan menu pengguna.
- Alias paket Livewire `database-notifications` juga diarahkan ke komponen kustom setelah boot, dan modal detail memeriksa dukungan state/aksi sebelum mengaksesnya agar request lama atau alias bawaan tidak memicu PropertyNotFoundException.
- Status baca notifikasi ditunda di session dan diterapkan sekaligus ketika Logout; bell tetap menampilkan item yang dipilih selama sesi aktif. Halaman Notifikasi selalu menampilkan riwayat lengkap. Badge bell disembunyikan setelah bell diklik dan statusnya diingat selama sesi tab.
- Halaman Notifikasi Admin Instansi memakai kategori tersimpan untuk label Informasi, Pengumuman, atau Sistem; notifikasi dengan `event_id` ditampilkan sebagai Pengajuan. Tab filter dan hitungannya mengikuti kategori tersebut.
- Badge jumlah notifikasi bell memakai merah cerah, outline kontras, dan posisi lebih dekat ke ikon bell.

- Tombol “Tandai semua sebagai dibaca” pada halaman Notifikasi Admin Instansi memakai komponen tombol Filament dengan spinner dan state disabled bawaan saat batch diproses; warna primary dan bobot teks diperkuat agar lebih mudah terlihat.
- Saat notifikasi dibaca dari halaman maupun sidebar, komponen bell menerima event refresh agar jumlah unread dan label status baca langsung sinkron; aksi tandai semua juga menyegarkan sidebar dalam sesi yang sama.
- Kartu notifikasi Admin Instansi menampilkan label jelas Belum dibaca/Sudah dibaca di samping kategori; daftar tetap memakai pagination lima item per halaman.
- Mengikuti proposal image41.png, label status teks di kartu notifikasi dihapus; indikator baca dipindah sejajar dengan waktu di sisi kanan, berupa lingkaran merah lebih besar untuk unread dan lingkaran outline abu-abu untuk read.
- Kartu notifikasi pada halaman Admin Instansi sekarang menampilkan cursor pointer, efek hover/fokus, dan bisa dibuka dengan Enter/Space selain klik.
- Hover kartu notifikasi kini memenuhi lebar container karena jarak horizontal dipindah dari margin ke padding kartu; label jenis dibedakan dengan warna Pengajuan/Ungu, Informasi/Biru, Pengumuman/Amber, Sistem/Abu-abu. Label kategori modal diterjemahkan ke Bahasa Indonesia dan diberi kontras warna lebih tinggi; isi modal memakai teks lebih terang.
- Bell sidebar menampilkan maksimal lima notifikasi terbaru tanpa pagination. Halaman Admin Instansi khusus Informasi Verifikasi dan Pengumuman beserta view/route Filament dinamisnya dihapus; menu Notifikasi ditempatkan sebelum Pengaturan Akun.
- Kartu identitas role di sidebar diberi margin bawah 1rem agar terpisah jelas dari item Dashboard.
- Aksi tabel ViewAction dan custom Action bernama view diseragamkan ke warna gray/neutral; aksi Edit tetap warning dan aksi persetujuan tetap success.
- Dashboard kini mengikuti susunan proposal role: Admin Instansi memiliki empat statistik, grafik/status pengajuan, tabel pengajuan, dan Pengumuman Terbaru; Super Admin memiliki lima statistik, grafik dan daftar pengajuan, breakdown kategori, event mendatang, serta timeline aktivitas. Daftar pengajuan, event mendatang, aktivitas, dan pengumuman masing-masing dibatasi lima item terbaru.
- Dashboard Admin Instansi: kolom Grafik Statistik Pengajuan diperlebar menjadi 7/12 dan Status Pengajuan dipersempit menjadi 5/12; kartu statistik dipadatkan (padding, jarak, ukuran teks), diagram doughnut/status diperkecil dan legend memuat jumlah serta persentase.
- Widget Pengumuman Terbaru pada dashboard Admin Instansi mengambil maksimal lima notification database berkategori announcement untuk akun aktif; tautan Lihat Semua membuka halaman Notifikasi dengan filter/tab Pengumuman aktif dan panel memakai ikon megafon.
- Halaman portal Pengumuman dan strip beranda mengambil notifikasi broadcast bertipe announcement yang sudah terkirim, dari catatan pengiriman Super Admin agar satu broadcast tidak berulang sebanyak penerima. CRUD/model/tabel announcements terpisah dihapus.
- Dashboard Admin: kartu statistik diubah ke layout ringkas dengan ikon kiri, nilai dan deskripsi sejajar; chart Status Pengajuan memakai rasio aspek alami agar doughnut tampil, dan legend jumlah/persentase disusun dari label data PHP. Heading Pengajuan Terbaru memakai ikon clipboard.
- Portal publik mendapat penyegaran visual terpadu melalui `public/css/portal-redesign.css`: palet hijau hutan, kertas hangat, dan aksen tanah liat; tipografi editorial Georgia dipadukan dengan sans-serif sistem; ukuran teks dan kontrol diperjelas; grid, kartu, kalender, sidebar, dan navigasi disetel responsif. Hero beranda memakai poster dari event terdekat bila tersedia, dengan fallback kategori tanpa membuat gambar tiruan. Gaya mengikuti skill `responsive-design`, `layout-grid`, `typography-scale`, `color-system`, dan visual critique dari koleksi `D:\joki\_designer-skills-review`.
- Pass lanjutan portal publik memberi karakter lebih hangat dan hidup: hero memakai komposisi asimetris dengan foto event edge-to-edge, kartu kategori memakai variasi warna tanah/palem yang terkontrol, sudut dan hover terasa lebih lembut, kartu event/galeri lebih berfokus pada foto, serta animasi reveal diberi easing lebih natural dan tetap menghormati reduced motion. Mengacu pada skill `aesthetic-usability`, `interfaces-that-feel`, `animation-principles`, dan `critique-color`.
- Pencarian di hero beranda publik hanya menampilkan kolom kata kunci dan tombol Cari; filter kategori tersembunyi yang sebelumnya dibuka tombol Filter telah dihapus.
- Motion portal diperkaya dengan entrance singkat bertahap pada hero, settle halus pada foto, serta reveal pada section utama galeri, kalender, pengumuman, dan detail event. Tab kategori aktif di galeri memakai latar hijau dan teks putih, bukan hitam; semua gerak tetap dinonaktifkan bila pengguna memilih reduced motion.
- Kartu Kategori Galeri di sidebar halaman Galeri hanya tampil di desktop (lebar di atas 1023px); sidebar Galeri Populer tetap tersedia di tablet dan HP.
- Beranda publik diringkas agar terasa editorial dan tidak seperti katalog formal: hero memakai headline natural, subteks penjelas di bagian kategori/agenda dihapus, judul diringkas menjadi “Ragam perayaan” dan “Yang akan datang”, serta kabar hanya muncul bila ada pengumuman. Saat tersedia lima event, agenda memakai satu kartu sorotan asimetris di samping empat kartu ringkas; layout kembali reguler pada layar lebih kecil.
