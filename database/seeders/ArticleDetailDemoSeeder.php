<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Database\Seeders\Support\DemoMedia;
use Illuminate\Database\Seeder;

class ArticleDetailDemoSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::query()->where('role', 'super_admin')->orderBy('id')->first();

        if (! $author) {
            throw new \RuntimeException('ArticleDetailDemoSeeder membutuhkan akun Super Admin yang sudah ada. Seeder tidak membuat pengguna.');
        }

        $excerpt = 'Mengenal cerita, kesenian, dan semangat komunitas yang bertemu dalam Festival Budaya Tana Luwu di Kota Palopo.';

        Article::query()->updateOrCreate(
            ['slug' => 'simulasi-cerita-festival-budaya-tana-luwu'],
            [
                'user_id' => $author->id,
                'title' => 'Simulasi: Cerita di Balik Festival Budaya Tana Luwu',
                'excerpt' => $excerpt,
                'body' => <<<'HTML'
<p>Festival Budaya Tana Luwu menjadi ruang perjumpaan bagi masyarakat, seniman, dan generasi muda untuk merayakan warisan budaya yang hidup di sekitar mereka. Kegiatan ini menyatukan pertunjukan, cerita sejarah, kerajinan, dan kuliner dalam suasana yang terbuka untuk semua.</p>

<h2>Warisan yang terus bergerak</h2>
<p>Tradisi bukan sekadar peninggalan masa lalu. Ia tumbuh melalui orang-orang yang terus mempraktikkan, mengajarkan, dan membagikannya. Di festival ini, pengunjung dapat menyaksikan musik tradisional dan gerak tari yang diwariskan lintas generasi.</p>
<p>Komunitas lokal juga membuka ruang berbagi tentang makna motif kerajinan dan kisah yang menyertai kehidupan masyarakat Tana Luwu. Setiap penampil membawa sudut pandang dan pengalaman yang membuat acara terasa dekat dengan keseharian.</p>

<h2>Ruang belajar untuk semua</h2>
<p>Selain pertunjukan utama, pengunjung dapat mengikuti lokakarya singkat dan berbincang langsung dengan para pelaku budaya. Anak-anak dan keluarga bisa mengenal permainan tradisional, sementara pengunjung lain dapat menikmati pameran karya dan sajian khas daerah.</p>
<ul>
    <li>Pertunjukan musik dan tari tradisional.</li>
    <li>Pameran kerajinan dan cerita di balik proses pembuatannya.</li>
    <li>Lokakarya budaya yang terbuka bagi pengunjung.</li>
</ul>

<h2>Datang dan ikut merayakan</h2>
<p>Festival ini mengajak masyarakat untuk hadir, belajar, dan merayakan budaya bersama. Informasi jadwal, lokasi, dan dokumen acara tersedia pada halaman detail Festival Budaya Tana Luwu.</p>
<blockquote><p>Budaya tetap hidup ketika dibagikan dan dirayakan bersama.</p></blockquote>
HTML,
                'image' => DemoMedia::placeholder(),
                'published_at' => now()->subHours(3),
            ],
        );

        $this->command?->info('Data simulasi detail berita siap: /berita/simulasi-cerita-festival-budaya-tana-luwu');
    }
}
