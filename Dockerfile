# --- STAGE 1: Mengompilasi Aset Frontend ---
FROM node:20-alpine AS frontend-builder
WORKDIR /app

# Copy package.json untuk caching layer
COPY package*.json ./
RUN npm ci

# Copy seluruh source code dan compile assets via Vite
COPY . .
RUN npm run build

# --- STAGE 2: PHP Application (Container Akhir) ---
FROM php:8.3-fpm

# Install dependensi sistem
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install ekstensi PHP yang dibutuhkan Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Copy Composer terbaru
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy seluruh source code Laravel
COPY . /var/www

# COPY hasil kompilasi CSS/JS dari Stage 1 (Frontend Builder) ke Container PHP
COPY --from=frontend-builder /app/public/build /var/www/public/build

# Install dependensi composer (Mode Produksi)
RUN composer install --no-dev --optimize-autoloader

# Atur entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["entrypoint.sh"]
