# Dùng môi trường PHP 8.2 và Apache
FROM php:8.2-apache

# Cài đặt các thư viện cần thiết cho Laravel và PostgreSQL
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Bật rewrite module của Apache (Rất quan trọng cho Laravel)
RUN a2enmod rewrite

# Cấu hình lại thư mục gốc của Apache trỏ vào thư mục /public của Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy toàn bộ code hiện tại vào trong container
COPY . /var/www/html

# Cài đặt Composer để tải thư viện Laravel
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Cấp quyền cho các thư mục cache và storage để Laravel không bị lỗi Permission
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Mở cổng 80
EXPOSE 80