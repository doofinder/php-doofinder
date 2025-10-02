# Dockerfile for php-doofinder tests
ARG PHP_VERSION=7.4
FROM php:${PHP_VERSION}-cli

# Install system deps (Composer, extensions)
RUN apt-get update && apt-get install -y \
    git unzip libcurl4-openssl-dev pkg-config libxml2-dev \
    && docker-php-ext-install curl dom \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-scripts --dev --optimize-autoloader

# Copy source code
COPY . .

# Default command: run tests
CMD ["composer", "tests"]
