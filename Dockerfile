FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    zip \
    curl \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath xml zip gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts
COPY . ./
RUN mkdir -p storage/framework/cache storage/framework/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache && chmod -R 0777 storage bootstrap/cache
RUN if [ ! -f .env ]; then cp .env.example .env && php artisan key:generate --force --no-interaction; fi
RUN php artisan package:discover --ansi

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 10000
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
