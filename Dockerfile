FROM php:8.2-apache
RUN a2enmod rewrite && service apache2 restart

WORKDIR /var/www/html
COPY . /var/www/html
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/src|g' /etc/apache2/sites-available/000-default.conf
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html
RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip nano \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install
EXPOSE 80