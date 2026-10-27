@extends('layouts.portal')

@section('title', 'Daftar Instansi — Ruang Budaya Palopo')

@section('content')
    <section class="register-page">
        <div class="register-intro">
            <div class="eyebrow"><span class="eyebrow-line"></span> UNTUK PENYELENGGARA</div>
            <h1>Mulai bagikan<br><em>cerita budaya.</em></h1>
            <p>Daftarkan instansi agar dapat mengelola dan mengajukan festival atau event budaya di Kota Palopo.</p>

            <div class="register-benefits">
                <h2>Dengan akun ini, Anda dapat</h2>
                <ul>
                    <li><span aria-hidden="true">✓</span> Mengelola informasi instansi</li>
                    <li><span aria-hidden="true">✓</span> Mengajukan festival dan event</li>
                    <li><span aria-hidden="true">✓</span> Memantau status pengajuan</li>
                </ul>
            </div>

            <aside class="register-next-info">
                <span class="register-info-icon" aria-hidden="true">i</span>
                <p>Setelah akun dibuat, Anda bisa masuk dan mulai mengajukan festival atau event.</p>
            </aside>
        </div>

        <form class="register-form" method="POST" action="{{ route('register.agency.store') }}" data-registration-wizard>
            @csrf
            <header class="register-form-heading">
                <div>
                    <span class="register-form-kicker">PENDAFTARAN INSTANSI</span>
                    <h2>Buat akun penyelenggara</h2>
                    <p>Lengkapi tiga tahap berikut untuk memulai.</p>
                </div>
                <span class="register-step-count" data-step-count>Tahap 1 dari 3</span>
            </header>

            <ol class="register-stepper" aria-label="Tahapan pendaftaran">
                <li class="is-active" data-step-indicator aria-current="step"><span>1</span><small>Instansi</small></li>
                <li data-step-indicator><span>2</span><small>Lokasi</small></li>
                <li data-step-indicator><span>3</span><small>Akun</small></li>
                <li class="register-stepper-track" aria-hidden="true"><span data-step-progress></span></li>
            </ol>

            @if ($errors->any())
                <div class="form-alert" role="alert">Mohon periksa kembali data yang Anda masukkan. Tahap dengan kesalahan akan dibuka.</div>
            @endif

            <fieldset class="register-step" data-registration-step data-step-title="Data instansi">
                <legend class="sr-only">Data instansi</legend>
                <div class="register-fields">
                    <label class="register-field register-field-wide">
                        Nama instansi <span>*</span>
                        <input name="agency_name" value="{{ old('agency_name') }}" autocomplete="organization" placeholder="Contoh: Sanggar Seni Lestari" required>
                        @error('agency_name')<small>{{ $message }}</small>@enderror
                    </label>
                    <label class="register-field">
                        Jenis instansi <span>*</span>
                        <select name="agency_type" required>
                            <option value="">Pilih jenis instansi</option>
                            @foreach ($agencyTypes as $type)
                                <option value="{{ $type }}" @selected(old('agency_type') === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('agency_type')<small>{{ $message }}</small>@enderror
                    </label>
                    <label class="register-field">
                        Email instansi <span>*</span>
                        <input type="email" name="agency_email" value="{{ old('agency_email') }}" autocomplete="email" placeholder="nama@instansi.id" required>
                        @error('agency_email')<small>{{ $message }}</small>@enderror
                    </label>
                    <label class="register-field register-field-wide">
                        Nomor telepon / WhatsApp <small class="register-field-optional">Opsional</small>
                        <input type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel" placeholder="08xx xxxx xxxx">
                        @error('phone')<small>{{ $message }}</small>@enderror
                    </label>
                </div>
                <div class="register-step-actions register-step-actions-end">
                    <button class="register-next" type="button" data-step-next>Lanjutkan <span aria-hidden="true">→</span></button>
                </div>
            </fieldset>

            <fieldset class="register-step" data-registration-step data-step-title="Lokasi instansi" hidden disabled>
                <legend class="sr-only">Lokasi instansi</legend>
                <p class="register-step-description">Pilih wilayah dari tingkat provinsi hingga kelurahan, lalu lengkapi alamat jalan.</p>
                <div class="register-fields register-region-fields">
                    <label class="register-field">
                        Provinsi <span>*</span>
                        <select name="region_province" data-region="provinces" data-old-value="{{ old('region_province') }}" required><option value="">Memuat provinsi...</option></select>
                        <span class="region-loading" data-region-loading hidden><span class="region-spinner" aria-hidden="true"></span> Memuat data wilayah...</span>
                    </label>
                    <label class="register-field">
                        Kabupaten / kota <span>*</span>
                        <select name="region_regency" data-region="regencies" data-old-value="{{ old('region_regency') }}" disabled required><option value="">Pilih provinsi dahulu</option></select>
                        <span class="region-loading" data-region-loading hidden><span class="region-spinner" aria-hidden="true"></span> Memuat data wilayah...</span>
                    </label>
                    <label class="register-field">
                        Kecamatan <span>*</span>
                        <select name="region_district" data-region="districts" data-old-value="{{ old('region_district') }}" disabled required><option value="">Pilih kabupaten / kota dahulu</option></select>
                        <span class="region-loading" data-region-loading hidden><span class="region-spinner" aria-hidden="true"></span> Memuat data wilayah...</span>
                    </label>
                    <label class="register-field">
                        Kelurahan / desa <span>*</span>
                        <select name="region_village" data-region="villages" data-old-value="{{ old('region_village') }}" disabled required><option value="">Pilih kecamatan dahulu</option></select>
                        <span class="region-loading" data-region-loading hidden><span class="region-spinner" aria-hidden="true"></span> Memuat data wilayah...</span>
                    </label>
                    <p class="region-error" data-region-error role="alert" hidden>Pilih provinsi, kabupaten/kota, kecamatan, dan kelurahan. Jika daftar gagal dimuat, muat ulang halaman lalu coba kembali.</p>
                    <label class="register-field register-field-wide">
                        Alamat jalan dan patokan <span>*</span>
                        <textarea name="address" rows="3" required placeholder="Contoh: Jl. Andi Djemma No. 10, dekat kantor kelurahan">{{ old('address') }}</textarea>
                        @error('address')<small>{{ $message }}</small>@enderror
                    </label>
                </div>
                <div class="register-step-actions">
                    <button class="register-back" type="button" data-step-back><span aria-hidden="true">←</span> Kembali</button>
                    <button class="register-next" type="button" data-step-next>Lanjutkan <span aria-hidden="true">→</span></button>
                </div>
            </fieldset>

            <fieldset class="register-step" data-registration-step data-step-title="Akun pengelola" hidden disabled>
                <legend class="sr-only">Akun pengelola</legend>
                <p class="register-step-description">Akun ini digunakan untuk masuk dan mengelola pengajuan festival atau event instansi.</p>
                <div class="register-fields">
                    <label class="register-field register-field-wide">
                        Nama lengkap pengelola <span>*</span>
                        <input name="name" value="{{ old('name') }}" autocomplete="name" placeholder="Nama orang yang bertanggung jawab" required>
                        @error('name')<small>{{ $message }}</small>@enderror
                    </label>
                    <label class="register-field register-field-wide">
                        Email untuk masuk <span>*</span>
                        <input type="email" name="email" value="{{ old('email') }}" autocomplete="username" placeholder="email yang akan digunakan untuk login" required>
                        @error('email')<small>{{ $message }}</small>@enderror
                    </label>
                    <label class="register-field">
                        Kata sandi <span>*</span>
                        <input type="password" name="password" required minlength="10" autocomplete="new-password" placeholder="Minimal 10 karakter">
                        @error('password')<small>{{ $message }}</small>@enderror
                    </label>
                    <label class="register-field">
                        Ulangi kata sandi <span>*</span>
                        <input type="password" name="password_confirmation" required minlength="10" autocomplete="new-password" placeholder="Ketik ulang kata sandi">
                    </label>
                </div>
                <p class="register-password-note">Gunakan kata sandi yang kuat dan jangan bagikan kepada orang lain.</p>
                <div class="register-step-actions">
                    <button class="register-back" type="button" data-step-back><span aria-hidden="true">←</span> Kembali</button>
                    <button class="register-submit" type="submit" data-loading-submit>
                        <span data-submit-label>Buat akun instansi <span aria-hidden="true">→</span></span>
                        <span data-submit-loading hidden><span class="submit-spinner" aria-hidden="true"></span> Mengirim pendaftaran...</span>
                    </button>
                </div>
            </fieldset>

            <p class="form-foot">Sudah punya akun? <a href="/admin/login">Masuk ke dashboard</a></p>
        </form>
    </section>
@endsection
