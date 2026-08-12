FROM php:8.2-apache

# Install dependency PostgreSQL dev library
RUN apt-get update && apt-get install -y libpq-dev

# Install ekstensi PDO dan PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql

# Copy semua file project ke container
COPY . /var/www/html/

# Expose port 8080 biar Railway bisa akses
EXPOSE 8080
