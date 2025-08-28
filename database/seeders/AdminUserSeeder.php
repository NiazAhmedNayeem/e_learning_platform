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
                'unique_id' => 'SA20250101',
                'name'      => 'Niaz Ahmed Nayeem',
                'email'     => 'niazahmed.net@gmail.com',
                'password'  => bcrypt('11223344'),
                'role'      => 'admin',
                'is_super'  => 1,
            ],
            [
                'unique_id' => 'A20250102',
                'name'      => 'Admin',
                'email'     => 'admin@gmail.com',
                'password'  => bcrypt('11223344'),
                'role'      => 'admin',
                'is_super'  => 0,
            ],
            [
                'unique_id' => 'T20250101',
                'name'      => 'Teacher Ahmed',
                'email'     => 'teacher@gmail.com',
                'password'  => bcrypt('11223344'),
                'role'      => 'teacher',
                'is_super'  => 0,
            ],
            [
                'unique_id' => 'S20250101',
                'name'      => 'Tonmoy',
                'email'     => 'student@gmail.com',
                'password'  => bcrypt('11223344'),
                'role'      => 'student',
                'is_super'  => 0,
            ],
        ]);
    }
}
