<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'nama' => 'Administrator SPK',
                'password' => Hash::make('admin123'),
                'jabatan' => 'Staf Pelaksana Desa',
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['username' => 'kades'],
            [
                'nama' => 'Sulaimi, H',
                'password' => Hash::make('kades123'),
                'jabatan' => 'Pembakal Desa Thaibah Raya',
                'role' => 'kepala_desa',
            ]
        );
    }
}
