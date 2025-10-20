# Используем официальный образ PHP 7.4 CLI от Docker Hub
FROM php:7.4-cli

# Установка зависимостей системы
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        git \
        unzip \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        zlib1g-dev \
        libzip-dev \
        libssl-dev \
        curl \
        gnupg \
        ca-certificates \
        locales \
        libpq-dev && \
    rm -rf /var/lib/apt/lists/*

# Установка PHP расширений
RUN docker-php-ext-install -j$(nproc) gd mbstring xml zip exif pcntl pdo pdo_pgsql

# Копирование Composer из образа composer:2
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Добавление пользователя и установка прав
RUN useradd -ms /bin/bash appuser
WORKDIR /app
USER appuser