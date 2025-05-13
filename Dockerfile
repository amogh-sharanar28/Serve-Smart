# Use official PHP Apache image
FROM php:8.1-apache

# Install MySQL extension for PHP
RUN docker-php-ext-install mysqli

# Copy project files into Apache's web directory
COPY . /var/www/html/

# Expose port 80
EXPOSE 80
