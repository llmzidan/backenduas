FROM php:8.2-apache

# Install extension yang dibutuhin Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev zlib1g-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql gd zip

# Aktifkan mod_rewrite Apache (Penting buat route Laravel)
RUN a2enmod rewrite

# Set Document Root ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy project ke dalam container
WORKDIR /var/www/html
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Set permission folder storage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
