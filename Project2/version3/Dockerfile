# Use an official PHP runtime as a parent image
FROM php:7.4-apache

# Enable Apache modules
RUN a2enmod rewrite

# Set the working directory
WORKDIR /var/www/html

# Copy the current directory contents into the container at /var/www/html
COPY . /var/www/html

# Install Composer and dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install

# Set environment variables for AWS credentials
ENV AWS_ACCESS_KEY_ID=AKIAYS2NVKP5OW2AZHGF
ENV AWS_SECRET_ACCESS_KEY=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

# Expose port 80
EXPOSE 80
