# 1. Use PHP 8.2 with Apache
FROM php:8.2-apache

# 2. Install Linux tools and Node.js
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev

# 3. INSTALL MYSQL DRIVER (This is the critical fix)
RUN docker-php-ext-install pdo pdo_mysql

# 4. Install Node.js for Vue
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# 5. Enable Apache Rewrite Module
RUN a2enmod rewrite

# 6. Set the document root to 'public'
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 7. Copy your application code
WORKDIR /var/www/html
COPY . .

# 8. Install PHP Dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 9. Install Vue Dependencies and Build
RUN npm install
RUN npm run build

# 10. Create Storage Folders (Fixes "No such file" error)
RUN mkdir -p /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/logs \
    /var/www/html/bootstrap/cache

# 11. Fix Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache