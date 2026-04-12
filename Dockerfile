# Use the official PHP 8.4 image from Docker Hub
FROM php:8.4-cli

RUN apt-get update && apt-get install -y unzip
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy current directory contents into the container
COPY . .

RUN composer install

CMD ["php", "bin/cli.php"]
