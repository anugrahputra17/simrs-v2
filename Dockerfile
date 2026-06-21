FROM php:8.3-fpm
RUN apt-get update && apt-get install -y git curl libpng-dev libonig-dev libxml2-dev zip unzip && apt-get clean && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
COPY . /var/www
RUN composer install --no-dev --optimize-autoloader
COPY docker/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh
EXPOSE 9000
ENTRYPOINT ["entrypoint.sh"]