FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    && docker-php-ext-install \
        intl \
        opcache \
        pdo_mysql \
        zip \
        bcmath \
    && rm -rf /var/lib/apt/lists/*

# Recommended PHP settings for Symfony
RUN { \
      echo "memory_limit=512M"; \
      echo "opcache.enable=1"; \
      echo "opcache.enable_cli=1"; \
      echo "opcache.validate_timestamps=0"; \
    } > /usr/local/etc/php/conf.d/symfony.ini || true

# Install Composer globally
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

WORKDIR /var/www/html

CMD ["php-fpm"]
