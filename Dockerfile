# Use the official Ubuntu image as the base image
FROM ubuntu:20.04

# Prevents interaction during package installation
ARG DEBIAN_FRONTEND=noninteractive

# Update the package repository and install necessary packages
RUN apt-get update && \
    apt-get install -y apache2 php libapache2-mod-php && \
    apt-get clean

# Create the web server root directory if it doesn't exist
RUN mkdir -p /var/www/html

# Copy the contents of the version2 directory to the Apache web server root
COPY version2/ /var/www/html/

# Set the appropriate permissions for the web server root
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apachectl", "-D", "FOREGROUND"]

