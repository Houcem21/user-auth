# Start from the official PHP 8.2 Apache image
FROM php:8.2-apache

# Install system dependencies required for PostgreSQL and Composer
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    && docker-php-ext-install pdo_pgsql

# Enable the Apache rewrite module
RUN a2enmod rewrite

# Install Composer (PHP dependency manager)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Copy composer files and install dependencies
COPY ./src/composer.json .
RUN composer install --no-interaction --no-plugins --no-scripts

# Copy the rest of the application code
COPY ./src/ .

# Expose port 80
EXPOSE 80