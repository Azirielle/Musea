# 1. Use PHP 8.2 with Apache
FROM php:8.2-apache

# 2. Install Linux tools and Node.js
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev

# 3. INSTALL MYSQL DRIVER
RUN docker-php-ext-install pdo pdo_mysql

# 3.1 Configure PHP Limits
RUN echo "upload_max_filesize = 64M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 64M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/uploads.ini

# 4. Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# 5. Enable Apache Rewrite Module
RUN a2enmod rewrite

# 6. Set Document Root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 7. Copy Code
WORKDIR /var/www/html
COPY . .

# 8. Install PHP Dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 9. Install Vue Dependencies
RUN npm install
RUN npm run build

# 10. Create Storage Folders
RUN mkdir -p /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/logs \
    /var/www/html/bootstrap/cache

# 11. Fix Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 12. CREATE STARTUP SCRIPT
# We added 'php artisan optimize:clear' to delete old cached files
RUN echo "#!/bin/sh\nphp artisan optimize:clear\nphp artisan migrate --force\napache2-foreground" > /start.sh
RUN chmod +x /start.sh

# 13. TELL DOCKER TO USE THIS SCRIPT
CMD ["/start.sh"]