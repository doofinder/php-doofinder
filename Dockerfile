# Dockerfile for php-doofinder tests
FROM php:7.4-cli

# Install system deps (Composer, extensions)
RUN apt-get update && apt-get install -y \
    git unzip libcurl4-openssl-dev pkg-config libxml2-dev \
    && docker-php-ext-install curl dom \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Default command: run tests
CMD ["composer", "tests"]
