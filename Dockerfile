FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    git \
    unzip \
    libzip-dev \
    icu-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    bash

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip intl gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Setup permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Copy Nginx config
RUN printf 'server {\n\
    listen ${PORT};\n\
    root /var/www/html/public;\n\
    add_header X-Frame-Options "SAMEORIGIN";\n\
    add_header X-Content-Type-Options "nosniff";\n\
    index index.php;\n\
    charset utf-8;\n\
    location / {\n\
        try_files $uri $uri/ /index.php?$query_string;\n\
    }\n\
    location = /favicon.ico { access_log off; log_not_found off; }\n\
    location = /robots.txt  { access_log off; log_not_found off; }\n\
    error_page 404 /index.php;\n\
    location ~ \.php$ {\n\
        fastcgi_pass 127.0.0.1:9000;\n\
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;\n\
        include fastcgi_params;\n\
    }\n\
    location ~ /\.(?!well-known).* {\n\
        deny all;\n\
    }\n\
}' > /etc/nginx/http.d/default.conf

# Start script
RUN printf '#!/bin/sh\n\
php artisan migrate --force\n\
php-fpm -D\n\
nginx -g "daemon off;"\n' > /usr/local/bin/start-app.sh && chmod +x /usr/local/bin/start-app.sh

EXPOSE 8080

CMD ["/usr/local/bin/start-app.sh"]
