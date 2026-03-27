<?php

namespace Database\Seeders;

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
        // 1. Tạo 1 tài khoản Admin cố định
        User::create([
            'name' => 'Quản trị viên',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'), 
            'role' => 1, 
            'phone' => '0999888777',
            'class' => null,
        ]);

        // 2. Danh sách lớp để bốc thăm ngẫu nhiên
        $classes = ['12A1', 'CNTT1', 'KTPM2', 'ĐTVT1'];

        // 3. Tạo 10 sinh viên bằng vòng lặp (Dùng dữ liệu cứng kết hợp số thứ tự)
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "Sinh viên " . $i,
                'email' => "sinhvien" . $i . "@gmail.com",
                'password' => Hash::make('123456'),
                'role' => 0, 
                'phone' => '012345678' . ($i - 1),
                'class' => $classes[array_rand($classes)], // Bốc thăm lớp ngẫu nhiên
            ]);
        }
    }
}
