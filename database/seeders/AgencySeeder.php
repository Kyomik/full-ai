<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\AgencyType;
use Illuminate\Database\Seeder;

class AgencySeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Sanggar Tari Bunga Masamba', 'Komunitas Budaya Luwu Raya', 'Forum Pemuda Kreatif Wara',
            'Sanggar Musik Tradisional Tana Luwu', 'Lembaga Seni SaokotaE', 'Komunitas Fotografi Batara Guru',
            'Rumah Kreatif Labombo', 'Paguyuban Seni Bassiang', 'Komunitas Penenun Rongkong',
            'Sanggar Teater La Galigo', 'Forum Sejarah Kota Palopo', 'Komunitas Musik Anak Pesisir',
            'Lembaga Budaya Mungkajang', 'Sanggar Seni Telluwanua', 'Komunitas Kuliner Luwu',
            'Forum Kreatif Wara Selatan', 'Pusat Kegiatan Seni Bara', 'Komunitas Warisan Budaya Battang',
            'Sanggar Pertunjukan Ponjalae', 'Lembaga Seni Tradisi Sendana',
        ];
        $types = AgencyType::query()->where('status', 'active')->orderBy('name')->pluck('name')->all()
            ?: ['Komunitas Budaya', 'Sanggar Seni', 'Lembaga Pendidikan', 'Organisasi Masyarakat', 'Lainnya'];

        foreach ($names as $index => $name) {
            $suffix = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);

            Agency::query()->updateOrCreate(
                ['email' => "instansi.demo{$suffix}@example.test"],
                [
                    'name' => "Demo: {$name}",
                    'type' => $types[$index % count($types)],
                    'phone' => '0812' . str_pad((string) (1000000 + $index), 7, '0', STR_PAD_LEFT),
                    'address' => 'Kota Palopo, Sulawesi Selatan',
                    'province_code' => null,
                    'regency_code' => null,
                    'district_code' => null,
                    'village_code' => null,
                    'status' => $index % 9 === 8 ? 'inactive' : 'active',
                ],
            );
        }

        $this->command?->info('20 instansi simulasi berhasil disiapkan. Tidak ada akun pengguna yang dibuat.');
    }
}
