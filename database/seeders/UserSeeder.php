<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Membuat akun contoh untuk ADMIN
        User::create([
            'name' => 'Andi Admin',
            'username' => 'admin01',
            'email' => 'admin@parkiros.com',
            'password' => Hash::make('rahasia123'),
            'role' => 'admin',
        ]);

        // 2. Membuat akun contoh untuk PETUGAS
        User::create([
            'name' => 'Budi Petugas',
            'username' => 'petugas01',
            'email' => 'petugas@parkiros.com',
            'password' => Hash::make('rahasia123'),
            'role' => 'petugas',
        ]);

        // 3. Membuat akun contoh untuk OWNER
        User::create([
            'name' => 'Pak Joko Owner',
            'username' => 'owner01',
            'email' => 'owner@parkiros.com',
            'password' => Hash::make('rahasia123'),
            'role' => 'owner',
        ]);
    }
}
