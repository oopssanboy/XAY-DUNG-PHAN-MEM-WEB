#!/usr/bin/env bash
set -o errexit

composer install --no-dev --optimize-autoloader

# Dùng migrate:fresh để dọn sạch và tạo mới hoàn toàn
php artisan migrate:fresh --seed --force
