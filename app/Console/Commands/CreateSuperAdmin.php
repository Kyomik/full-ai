<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateSuperAdmin extends Command
{
    protected $signature = 'app:make-super-admin';

    protected $description = 'Create a Super Admin account in the database';

    public function handle(): int
    {
        $name = $this->ask('Nama Super Admin');
        $email = $this->ask('Email Super Admin');

        $validator = Validator::make(compact('name', 'email'), [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        do {
            $password = $this->secret('Kata sandi (minimal 10 karakter)');
            $confirmation = $this->secret('Ulangi kata sandi');
            if (strlen((string) $password) < 10 || $password !== $confirmation) {
                $this->error('Kata sandi harus minimal 10 karakter dan kedua isian harus sama.');
                $password = null;
            }
        } while (!$password);

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => 'super_admin',
            'status' => 'active',
            'agency_id' => null,
        ]);

        $this->info("Super Admin {$email} berhasil disimpan ke database.");

        return self::SUCCESS;
    }
}
