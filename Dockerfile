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
    bash \
    nodejs \
    npm \
    postgresql-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql pgsql zip intl gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets
RUN npm install && npm run build

# Setup permissions
RUN chown -R www-data:www-data storage bootstrap/cache public

# Nginx config
RUN rm -rf /etc/nginx/http.d/* && printf 'server {\n\
    listen 8080 default_server;\n\
    listen [::]:8080 default_server;\n\
    server_name _;\n\
    root /var/www/html/public;\n\
    index index.php;\n\
    location / {\n\
        try_files $uri $uri/ /index.php?$query_string;\n\
    }\n\
    location ~ \.php$ {\n\
        include fastcgi_params;\n\
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n\
        fastcgi_pass 127.0.0.1:9000;\n\
    }\n\
    location ~ /\.(?!well-known).* {\n\
        deny all;\n\
    }\n\
}' > /etc/nginx/http.d/default.conf

# Supervisord config
RUN printf '[supervisord]\n\
nodaemon=true\n\
user=root\n\
[program:php-fpm]\n\
command=php-fpm -F\n\
stdout_logfile=/dev/stdout\n\
stdout_logfile_maxbytes=0\n\
stderr_logfile=/dev/stderr\n\
stderr_logfile_maxbytes=0\n\
[program:nginx]\n\
command=nginx -g "daemon off;"\n\
stdout_logfile=/dev/stdout\n\
stdout_logfile_maxbytes=0\n\
stderr_logfile=/dev/stderr\n\
stderr_logfile_maxbytes=0\n' > /etc/supervisord.conf

# Start script
RUN printf '#!/bin/sh\n\
php artisan migrate --force\n\
php artisan storage:link\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
/usr/bin/supervisord -c /etc/supervisord.conf\n' > /usr/local/bin/start-app.sh && chmod +x /usr/local/bin/start-app.sh

EXPOSE 8080

CMD ["/usr/local/bin/start-app.sh"]
