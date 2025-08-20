<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'admin',
                'email' => 'niazahmed.net@gmail.com',
                'password' => bcrypt('11223344'),
                'role' => 'admin',
            ],
            [
                'name' => 'Tonmoy',
                'email' => 'tonmoy@gmail.com',
                'password' => bcrypt('11223344'),
                'role' => 'student',
            ]
        ]);
    }
}
