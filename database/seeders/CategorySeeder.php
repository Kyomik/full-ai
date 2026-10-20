<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Seni & Budaya','Pariwisata','Ekonomi Kreatif','Pendidikan','Olahraga','Lainnya'] as $name) {
            Category::firstOrCreate(['name'=>$name]);
        }
    }
}
