FROM php:8.2-apache

# Install build tools and PostgreSQL client headers, then build pdo_pgsql
RUN apt-get update \
	&& apt-get install -y --no-install-recommends libpq-dev gcc make autoconf libc-dev \
	&& docker-php-ext-install pdo_pgsql \
	&& rm -rf /var/lib/apt/lists/*

# Copy project files into the container
COPY . /var/www/html/

# Apache in this image listens on port 80
EXPOSE 80
