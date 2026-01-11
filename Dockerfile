# 1. Use PHP 8.2 with Apache
FROM php:8.2-apache

# 2. Install Linux tools and Node.js (for Vue)
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# 3. Enable Apache Rewrite Module (Crucial for Laravel)
RUN a2enmod rewrite

# 4. Set the document root to 'public' (where index.php lives)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Copy your application code
WORKDIR /var/www/html
COPY . .

# 6. Install PHP Dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 7. Install Vue Dependencies and Build
RUN npm install
RUN npm run build

# 8. Fix Permissions (so Laravel can write to storage)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
