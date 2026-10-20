<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create a super admin
        User::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'status' => 'active',
        ]);

        // Create a regular agency admin (assuming an agency exists)
        $agency = \App\Models\Agency::first();
        if (! $agency) {
            $agency = \App\Models\Agency::factory()->create();
        }

        User::create([
            'agency_id' => $agency->id,
            'name' => 'Agency Admin',
            'username' => 'agencyadmin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'agency_admin',
            'status' => 'active',
        ]);
    }
}
