# Use the official PHP 8.4 image from Docker Hub
FROM php:8.4-cli AS base

RUN apt-get update && apt-get install -y unzip
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app
COPY composer.json composer.lock ./

FROM base AS production

RUN composer install --no-dev --optimize-autoloader
COPY src/ src/
COPY bin/ bin/

CMD ["php", "bin/cli.php"]

FROM base AS test

RUN pecl install pcov && docker-php-ext-enable pcov

RUN composer install
COPY . .
