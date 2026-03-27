#!/usr/bin/env bash
# Thoát ngay nếu có lỗi
set -o errexit

echo "--- Đang cài đặt các thư viện PHP ---"
composer install --no-dev --optimize-autoloader

echo "--- Đang tạo bảng dữ liệu (Migrate) ---"
# Lệnh này sẽ tự chạy migrate mà không cần hỏi yes/no
php artisan migrate --force

echo "--- Đang bơm dữ liệu mẫu (Seed) ---"
php artisan db:seed --force

echo "--- Deploy hoàn tất! ---"
