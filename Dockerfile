# Root Dockerfile: build the Laravel app that lives in the `web/` directory
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli mbstring exif pcntl bcmath gd

# Install Node 18 (for Laravel + Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Copy composer binary
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy only the web/ folder into the image so composer.json/composer.lock are present
COPY web/ .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install JS dependencies and build Vite
RUN npm ci && npm run build

# Permissions
RUN chmod -R 777 storage

# Enable Apache rewrite
RUN a2enmod rewrite

# Apache config for Laravel
RUN cat << 'EOF' > /etc/apache2/conf-available/laravel.conf
<Directory /var/www/html/public>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
EOF

RUN a2enconf laravel

# Set document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf

EXPOSE 80

# Do NOT migrate in CMD (handle migrations via deploy hook or run manually)
CMD apache2-foreground
