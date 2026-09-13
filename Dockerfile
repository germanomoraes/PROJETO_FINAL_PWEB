FROM php:8.2-cli

# Extensões necessárias para Laravel + PostgreSQL
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    postgresql-client \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 8000 10000

ENTRYPOINT ["docker-entrypoint.sh"]
