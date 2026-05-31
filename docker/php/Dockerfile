FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    ca-certificates \
    gnupg \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl calendar opcache

# Dev için PHP ve FPM ayarları:
#   - OPcache + realpath cache → Windows bind-mount stat çağrılarını minimize eder.
#   - pm.max_children 5 → 15 → eşzamanlı request kapasitesi.
COPY docker/php/php.dev.ini   /usr/local/etc/php/conf.d/zz-refik.ini
COPY docker/php/fpm-www.conf  /usr/local/etc/php-fpm.d/zz-refik.conf

# Node.js 22 LTS — Vite 8 + Tailwind v4 derlemek için
RUN mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_22.x nodistro main" > /etc/apt/sources.list.d/nodesource.list \
    && apt-get update \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN groupadd -g 1000 www && useradd -u 1000 -ms /bin/bash -g www www

WORKDIR /var/www

USER www
