<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::query()->where('role', 'super_admin')->orderBy('id')->first();

        if (! $author) {
            throw new \RuntimeException('ArticleSeeder membutuhkan akun Super Admin yang sudah ada. Seeder tidak membuat pengguna.');
        }

        $image = \Database\Seeders\Support\DemoMedia::placeholder();
        $titles = [
            'Mengenal Ragam Musik Tradisional Tana Luwu', 'Sanggar Tari Menjaga Gerak Warisan Daerah',
            'Cerita di Balik Tenun Khas Luwu', 'Kuliner Pesisir yang Menghidupkan Tradisi',
            'Teater Rakyat Kembali Hadir di Palopo', 'Menelusuri Jejak Sejarah Kedatuan Luwu',
            'Cerita Rakyat yang Terus Dikisahkan', 'Permainan Tradisional untuk Generasi Muda',
            'Seniman Lokal Berkarya di Ruang Publik', 'Fotografi Membingkai Warisan Budaya',
            'Makna Motif pada Busana Adat Luwu', 'Tradisi Lokal dalam Kehidupan Masyarakat',
            'Film Dokumenter Mengangkat Cerita Palopo', 'Kreator Muda Mengolah Potensi Lokal',
            'Belajar Budaya dari Lingkungan Sekitar', 'Rute Wisata Sejarah di Kota Palopo',
            'Mengenal Olahraga Tradisional Daerah', 'Komunitas Pesisir Merawat Tradisi',
            'Ruang Kreatif Menjadi Rumah bagi Seniman', 'Kolaborasi Seni untuk Budaya Palopo',
        ];

        foreach ($titles as $index => $title) {
            $slug = 'demo-' . Str::slug($title);
            $excerpt = 'Artikel simulasi nomor ' . ($index + 1) . ' tentang kegiatan, pelaku, dan warisan budaya di Kota Palopo.';

            Article::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'user_id' => $author->id,
                    'title' => 'Demo: ' . $title,
                    'excerpt' => $excerpt,
                    'body' => $excerpt . "\n\nKonten ini merupakan data fiktif untuk demonstrasi portal publik dan dashboard pengelola.",
                    'image' => $image,
                    'published_at' => now()->subDays(20 - $index),
                ],
            );
        }

        $this->command?->info('20 berita simulasi berhasil disiapkan.');
    }
}
