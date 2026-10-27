<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Alun-Alun Kota Palopo',
                'type' => 'open_space',
                'address' => 'Jalan Alun-Alun, Palopo',
                'latitude' => -3.0,
                'longitude' => 120.0,
                'status' => 'active',
            ],
            [
                'name' => 'Museum Balla Lompoa',
                'type' => 'building',
                'address' => 'Jalan Merdeka, Palopo',
                'latitude' => -3.1,
                'longitude' => 120.1,
                'status' => 'active',
            ],
        ];

        $demoNames = [
            'Lapangan Andi Djemma', 'Taman Kirab', 'Gedung Kesenian Batara Guru', 'Pelataran Istana Kedatuan Luwu',
            'Pantai Labombo Utara', 'Ruang Kreatif Wara', 'Aula Kecamatan Wara Timur', 'Panggung Terbuka Salubattang',
            'Taman Baca Mungkajang', 'Gedung Serbaguna Sendana', 'Area Wisata Latuppa', 'Balai Budaya Balandai',
            'Lapangan Pancasila Selatan', 'Rumah Adat Ponjalae', 'Pusat Kuliner Lagota', 'Taman Edukasi Songka',
            'Panggung Komunitas Battang', 'Aula Kreatif Tompotikka', 'Ruang Seni Dangerakko', 'Pelataran Pesisir Takkalala',
        ];
        $types = array_keys(Location::CATEGORIES);

        foreach ($demoNames as $index => $name) {
            $locations[] = [
                'name' => 'Demo: ' . $name,
                'type' => $types[$index % count($types)],
                'address' => 'Kota Palopo, Sulawesi Selatan',
                'latitude' => -2.9912 + ($index * 0.0011),
                'longitude' => 120.1975 + ($index * 0.0012),
                'status' => $index >= 18 ? 'inactive' : 'active',
            ];
        }

        foreach ($locations as $data) {
            if (str_starts_with($data['name'], 'Demo:')) {
                Location::query()->updateOrCreate(['name' => $data['name']], $data);
            } else {
                Location::query()->firstOrCreate(['name' => $data['name']], $data);
            }
        }
    }
}
