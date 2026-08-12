FROM php:8.2-cli

RUN apt-get update && apt-get install -y libpq-dev
RUN docker-php-ext-install pdo pdo_pgsql

WORKDIR /app
COPY . /app

# Gunakan shell supaya $PORT diekspansi
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t /app"]
