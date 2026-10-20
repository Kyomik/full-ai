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
                'province_code' => '33',
                'regency_code' => '3301',
                'district_code' => '330101',
                'village_code' => '33010101',
                'latitude' => -3.0,
                'longitude' => 120.0,
                'status' => 'active',
            ],
            [
                'name' => 'Museum Balla Lompoa',
                'type' => 'building',
                'address' => 'Jalan Merdeka, Palopo',
                'province_code' => '33',
                'regency_code' => '3301',
                'district_code' => '330102',
                'village_code' => '33010201',
                'latitude' => -3.1,
                'longitude' => 120.1,
                'status' => 'active',
            ],
        ];

        foreach ($locations as $data) {
            Location::create($data);
        }
    }
}
