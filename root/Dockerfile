FROM php:8.2-apache

# Install ekstensi PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql

# Copy semua file ke container
COPY . /var/www/html/

# Jalankan server di port 8080
EXPOSE 8080
