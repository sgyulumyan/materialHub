FROM php:8.5-fpm-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git unzip libpq-dev libicu-dev libzip-dev \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_pgsql bcmath intl zip pcntl \
    && pecl install redis-6.3.0 \
    && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/*

# SQLite and XML extensions are already provided by the official PHP image.
RUN php -r 'foreach (["pdo_pgsql", "pdo_sqlite", "redis", "mbstring", "dom", "xml", "xmlwriter", "pcntl"] as $extension) { if (!extension_loaded($extension)) { fwrite(STDERR, "Missing extension: ".$extension.PHP_EOL); exit(1); } }'

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY docker/php/php.ini /usr/local/etc/php/conf.d/materialhub.ini
COPY docker/php/init.sh /usr/local/bin/materialhub-init
RUN chmod +x /usr/local/bin/materialhub-init

WORKDIR /var/www/html
USER www-data
CMD ["php-fpm"]
