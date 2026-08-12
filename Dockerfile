FROM php:8.2-cli

RUN apt-get update && apt-get install -y libpq-dev
RUN docker-php-ext-install pdo pdo_pgsql

COPY . /app
WORKDIR /app

CMD php -S 0.0.0.0:${PORT:-8080}
