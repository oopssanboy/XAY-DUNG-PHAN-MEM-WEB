#!/usr/bin/env bash
# Thoát ngay nếu có lỗi
set -o errexit

echo "--- Cài đặt thư viện ---"
composer install --no-dev --optimize-autoloader

echo "--- Dọn dẹp và tạo lại bảng (Migrate Fresh) ---"
# Lệnh này sẽ xóa sạch bảng cũ và chạy lại từ đầu kèm Seeder
php artisan migrate:fresh --seed --force

echo "--- Đã tạo bảng và nạp dữ liệu mẫu thành công! ---"
