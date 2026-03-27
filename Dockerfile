FROM php:8.2-apache

# 1. Cài đặt các thư viện hệ thống (Thêm libzip-dev)
# Cài đặt các thư viện hệ thống cần thiết (Thêm libpq-dev cho PostgreSQL)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    # Cài đặt các extension PHP (Thêm pdo_pgsql)
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql zip
# Bật mod_rewrite cho Apache
RUN a2enmod rewrite

# Copy code vào thư mục web
COPY . /var/www/html

# Sửa quyền truy cập
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Đổi DocumentRoot sang /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Cài đặt Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader
RUN chmod +x /var/www/html/render-build.sh

# Chạy lệnh build và migrate
RUN /var/www/html/render-build.sh
# Mở cổng 80
EXPOSE 80
