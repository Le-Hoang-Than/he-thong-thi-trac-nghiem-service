# Dung moi truong PHP 8.2 va Apache
FROM php:8.2-apache

# Cai dat cac thu vien can thiet cho Laravel va PostgreSQL
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Bat rewrite module cua Apache (Quan trong de Laravel chay router)
RUN a2enmod rewrite

# Cau hinh lai thu muc goc cua Apache tro vao /public cua Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy toan bo code hien tai vao trong container
COPY . /var/www/html

# Cai dat Composer de tai thu vien Laravel
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Cap quyen cho cac thu muc cache va storage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Mo cong 80
EXPOSE 80