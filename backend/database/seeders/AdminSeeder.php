<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@findfound.test'],
            [
                'name'             => 'Admin Find & Found',
                'password'         => bcrypt('admin123'),
                'phone_number'     => '6281234567890',
                'instagram_handle' => 'findfound_admin',
                'domicile_city'    => 'Pati',
                'role'             => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@findfound.test'],
            [
                'name'             => 'Test User',
                'password'         => bcrypt('user1234'),
                'phone_number'     => '6289876543210',
                'instagram_handle' => 'testuser_pati',
                'domicile_city'    => 'Juwana',
                'role'             => 'user',
            ]
        );
    }
}
