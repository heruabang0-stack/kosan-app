<?php

namespace Database\Seeders;

use App\Models\Kos;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@kosan.com'],
            [
                'name'     => 'Administrator Kosan',
                'password' => Hash::make('password123'),
                'telepon'  => '081234567890',
                'role'     => 'admin',
            ]
        );

        // 2. Akun Pengguna Biasa
        User::updateOrCreate(
            ['email' => 'user@kosan.com'],
            [
                'name'     => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'telepon'  => '089876543210',
                'role'     => 'user',
            ]
        );

        // 3. Data Kos Awal
        Kos::updateOrCreate(
            ['nama_kos' => 'Kos Melati Eksklusif'],
            [
                'alamat'    => 'Jl. Kaliurang KM 5, Pandega Marta No. 12, Sleman, Yogyakarta',
                'latitude'  => -7.7554000,
                'longitude' => 110.3805000,
                'harga'     => 1200000,
                'tipe'      => 'Putri',
                'fasilitas' => 'AC, WiFi 50 Mbps, Kamar Mandi Dalam, Kasur Springbed, Lemari, Parkir Motor',
                'foto'      => null,
            ]
        );

        Kos::updateOrCreate(
            ['nama_kos' => 'Kos Garuda Mandiri'],
            [
                'alamat'    => 'Jl. Gejayan, Gang Bayu No. 5, Sleman, Yogyakarta',
                'latitude'  => -7.7650000,
                'longitude' => 110.3900000,
                'harga'     => 850000,
                'tipe'      => 'Putra',
                'fasilitas' => 'WiFi, Kamar Mandi Luar, Kasur, Meja Belajar, Parkir Luas, Dapur Bersama',
                'foto'      => null,
            ]
        );

        Kos::updateOrCreate(
            ['nama_kos' => 'Graha Indah Kost'],
            [
                'alamat'    => 'Jl. Magelang KM 4, Mlati, Sleman, Yogyakarta',
                'latitude'  => -7.7610000,
                'longitude' => 110.3620000,
                'harga'     => 1500000,
                'tipe'      => 'Campur',
                'fasilitas' => 'AC, Smart TV, Water Heater, WiFi, CCTV 24 Jam, Parkir Mobil & Motor',
                'foto'      => null,
            ]
        );
    }
}
