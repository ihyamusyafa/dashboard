FROM php:8.2-apache

# Install dependency PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev

# Install ekstensi PDO PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql

# Copy semua file project ke container
COPY . /var/www/html/

EXPOSE 8080
