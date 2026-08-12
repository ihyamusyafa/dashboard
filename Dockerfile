FROM php:8.2-apache

# Install dependency PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev

# Install ekstensi PDO PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql

# Copy semua file project ke container
COPY . /var/www/html/

# Railway akan kasih PORT lewat environment variable
CMD php -S 0.0.0.0:${PORT:-8080} -t /var/www/html
