FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY laravel/. .

RUN composer install --optimize-autoloader

RUN npm install && npm run build

RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000

CMD ["php-fpm"]