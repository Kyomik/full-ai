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
        // Assuming there are some users already; if not, create a dummy user.
        $user = User::first() ?? User::factory()->create();

        for ($i = 1; $i <= 5; $i++) {
            Article::create([
                'user_id' => $user->id,
                'title'   => "Artikel Contoh {$i}",
                'slug'    => Str::slug("Artikel Contoh {$i}"),
                'excerpt' => "Ringkasan artikel ke-{$i}.",
                'body'    => "Isi lengkap artikel contoh nomor {$i}.",
                'image'   => "images/article{$i}.jpg",
                'published_at' => now()->subDays($i),
            ]);
        }
    }
}
