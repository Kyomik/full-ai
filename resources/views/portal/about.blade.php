@extends('layouts.portal')
@section('title','Tentang Kami — Sistem Informasi Budaya Kota Palopo')
@section('content')
<div class="page-container content-page about-page">
    <div class="breadcrumbs">
        <a href="{{ route('home') }}">Beranda</a>
        <span>›</span> Tentang Kami
    </div>
    <header class="about-heading">
        <h1>Tentang Kami</h1>
        <p>Mengenal lebih dekat Sistem Informasi Festival & Event Budaya Kota Palopo.</p>
    </header>
    <section class="about-intro">
        <div class="about-image">
            {!! file_exists(public_path('images/palopo-seal.jpeg')) ? '<img src="'.asset('images/palopo-seal.jpeg').'" alt="Lambang Kota Palopo">' : '' !!}
        </div>
        <div class="about-copy">
            <h2>Sistem Informasi Festival & Event Budaya Kota Palopo</h2>
            <p>Sistem Informasi Festival & Event Budaya Kota Palopo hadir untuk mewujudkan Palopo sebagai daerah tujuan wisata yang berdaya saing, didukung budaya Luwu dan ekonomi kreatif untuk kesejahteraan masyarakat.</p>
            <p>Kami mengembangkan destinasi berkelanjutan, memperkuat desa wisata, wisata syariah, dan MICE, serta mempromosikan event dan peluang usaha kreatif melalui teknologi informasi bersama seluruh pemangku kepentingan.</p>
            <a class="outline-button" href="{{ route('events.index') }}">Jelajahi Festival & Event</a>
        </div>
        <aside class="sidebar-box about-facts">
            <h2>Informasi Singkat</h2>
            <p><strong>Dikelola oleh</strong>Dinas Pariwisata Kota Palopo</p>
            <p><strong>Visi</strong>Terwujudnya Kota Palopo sebagai daerah tujuan Wisata yang berdaya saing, didukung oleh budaya Luwu dan Ekonomi Kreatif untuk Kesejahteraan Masyarakat</p>
            <p><strong>Misi</strong>Mempromosikan pariwisata, budaya Luwu, dan ekonomi kreatif Kota Palopo melalui informasi event dan destinasi yang lengkap, akurat, dan mudah diakses.</p>
            <p><strong>Tujuan</strong>Meningkatkan daya saing pariwisata Palopo dan melestarikan budaya Luwu untuk kesejahteraan masyarakat.</p>
        </aside>
    </section>
    <section class="values-section">
        <h2>Nilai Kami</h2>
        <div class="values-grid">
            <article>
                <span>♧</span>
                <h3>Berbudaya Luwu</h3>
                <p>Mengangkat budaya Luwu sebagai kekayaan dan kearifan lokal Kota Palopo.</p>
            </article>
            <article>
                <span>♙</span>
                <h3>Berdaya Saing</h3>
                <p>Mengembangkan destinasi dan event wisata yang berkualitas dan berkelanjutan.</p>
            </article>
            <article>
                <span>♡</span>
                <h3>Kolaboratif</h3>
                <p>Menggandeng masyarakat, pelaku usaha, dan pemangku kepentingan dalam membangun pariwisata daerah.</p>
            </article>
            <article>
                <span>◎</span>
                <h3>Inovatif</h3>
                <p>Memanfaatkan teknologi informasi dan media untuk promosi dan pelayanan wisata yang lebih luas.</p>
            </article>
        </div>
    </section>
    <section class="agency-contact">
        <div>
            <h2>Dinas Pariwisata Kota Palopo</h2>
            <p>Pengelola informasi festival, event, dan kegiatan budaya Kota Palopo.</p>
        </div>
        <div class="contact-facts">
            <span>⌖ Jl. Andi Djemma, Kota Palopo, Sulawesi Selatan</span>
            <span>✉ Informasi kontak resmi dapat diperoleh melalui kanal Pemerintah Kota Palopo.</span>
        </div>
    </section>
</div>
@endsection
