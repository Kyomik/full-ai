<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Category;
use App\Models\CulturalEvent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class VerificationDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CategorySeeder::class);

        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin.demo@example.com'],
            [
                'name' => 'Super Admin Demo',
                'password' => 'password',
                'role' => 'super_admin',
                'status' => 'active',
            ],
        );

        $agencies = collect([
            ['name' => 'Dinas Pariwisata Kota Palopo', 'type' => 'Pemerintah'],
            ['name' => 'Dinas Koperasi & UMKM', 'type' => 'Pemerintah'],
            ['name' => 'Komunitas Runner Palopo', 'type' => 'Komunitas'],
            ['name' => 'Kecamatan Wara', 'type' => 'Pemerintah'],
        ])->mapWithKeys(function (array $data): array {
            $agency = Agency::firstOrCreate(
                ['email' => str($data['name'])->slug('-') . '@demo.palopo.go.id'],
                [...$data, 'phone' => '081200000000', 'address' => 'Kota Palopo', 'status' => 'active'],
            );

            User::firstOrCreate(
                ['email' => 'admin.' . $agency->id . '@demo.palopo.go.id'],
                [
                    'name' => 'Admin ' . $agency->name,
                    'password' => 'password',
                    'agency_id' => $agency->id,
                    'role' => 'agency_admin',
                    'status' => 'active',
                ],
            );

            return [$agency->name => $agency];
        });

        $categories = Category::query()->pluck('id', 'name');
        $today = Carbon::today();

        $events = [
            [
                'title' => 'Festival Seni & Budaya Palopo 2025',
                'agency' => 'Dinas Pariwisata Kota Palopo',
                'category' => 'Seni & Budaya',
                'status' => 'pending',
                'created_at' => $today->copy()->subDays(3)->setTime(10, 30),
                'starts_on' => $today->copy()->addDays(30),
                'ends_on' => $today->copy()->addDays(32),
                'venue' => 'Lapangan Pancasila Kota Palopo',
            ],
            [
                'title' => 'Pameran UMKM Kreatif Palopo',
                'agency' => 'Dinas Koperasi & UMKM',
                'category' => 'Ekonomi Kreatif',
                'status' => 'pending',
                'created_at' => $today->copy()->subDays(4)->setTime(9, 15),
                'starts_on' => $today->copy()->addDays(45),
                'ends_on' => $today->copy()->addDays(50),
                'venue' => 'Gedung SaokotaE Palopo',
            ],
            [
                'title' => 'Palopo Run Fest 2025',
                'agency' => 'Komunitas Runner Palopo',
                'category' => 'Olahraga',
                'status' => 'approved',
                'created_at' => $today->copy()->subDays(5)->setTime(14, 20),
                'starts_on' => $today->copy()->addDays(60),
                'ends_on' => $today->copy()->addDays(60),
                'venue' => 'Kawasan City Walk Palopo',
            ],
            [
                'title' => 'Festival Wisata SaokotaE',
                'agency' => 'Dinas Pariwisata Kota Palopo',
                'category' => 'Pariwisata',
                'status' => 'approved',
                'created_at' => $today->copy()->subDays(6)->setTime(11, 45),
                'starts_on' => $today->copy()->addDays(75),
                'ends_on' => $today->copy()->addDays(76),
                'venue' => 'Dinas Pariwisata Kota Palopo',
            ],
            [
                'title' => 'Lomba Perahu Tradisional',
                'agency' => 'Kecamatan Wara',
                'category' => 'Seni & Budaya',
                'status' => 'rejected',
                'created_at' => $today->copy()->subDays(7)->setTime(8, 50),
                'starts_on' => $today->copy()->addDays(90),
                'ends_on' => $today->copy()->addDays(90),
                'venue' => 'Kecamatan Wara',
            ],
        ];

        foreach ($events as $event) {
            $agency = $agencies[$event['agency']];
            $status = $event['status'];

            CulturalEvent::updateOrCreate(
                ['slug' => str($event['title'])->slug('-')],
                [
                    'agency_id' => $agency->id,
                    'category_id' => $categories[$event['category']],
                    'title' => $event['title'],
                    'theme' => $event['title'],
                    'description' => 'Data demo untuk pengujian halaman verifikasi pengajuan.',
                    'starts_on' => $event['starts_on'],
                    'ends_on' => $event['ends_on'],
                    'venue' => $event['venue'],
                    'address' => 'Kota Palopo',
                    'status' => $status,
                    'reviewed_by' => $status === 'pending' ? null : $superAdmin->id,
                    'reviewed_at' => $status === 'pending' ? null : $event['created_at']->copy()->addDay(),
                    'review_note' => $status === 'rejected' ? 'Data kegiatan perlu dilengkapi.' : null,
                    'created_at' => $event['created_at'],
                    'updated_at' => $event['created_at'],
                ],
            );
        }
    }
}
