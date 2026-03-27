<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Tạo 1 tài khoản Admin xịn xò cố định
        User::create([
            'name' => 'Quản trị viên',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'), 
            'role' => 1, 
            'phone' => '0999888777',
            'class' => null,
        ]);

        // 2. Dùng vòng lặp chạy 10 lần để đảm bảo mỗi lần là 1 lớp random khác nhau
        for ($i = 0; $i < 10; $i++) {
            User::factory()->create([
                'password' => Hash::make('123456'),
                'role' => 0, 
                'class' => fake()->randomElement(['12A1', 'CNTT1', 'KTPM2', 'ĐTVT1']), 
            ]);
        }
    }
}
