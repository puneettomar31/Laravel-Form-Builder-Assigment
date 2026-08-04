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
    zip \
    curl \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath xml zip gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN mkdir -p storage bootstrap/cache && chown -R www-data:www-data storage bootstrap/cache
COPY . ./
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN if [ ! -f .env ]; then cp .env.example .env && php artisan key:generate --force; fi

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 10000
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
