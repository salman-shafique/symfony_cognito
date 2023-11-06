# Dockerfile

# Use the specified PHP version with Alpine
FROM php:8.1-fpm-alpine

# Install dependencies for extensions and Symfony CLI
RUN apk add --no-cache \
        bash \
        libpng-dev \
        oniguruma-dev \
        zip \
        unzip

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy the application files to the container
COPY . /var/www/html

# Use the default development configuration for PHP
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
