# Use official PHP with Apache
FROM php:8.2-apache

# Enable necessary PHP extensions
RUN docker-php-ext-install session

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set the working directory
WORKDIR /var/www/html

# Copy application files
COPY index.php /var/www/html/
COPY process.php /var/www/html/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Create a session directory with proper permissions
RUN mkdir -p /var/www/html/sessions \
    && chown -R www-data:www-data /var/www/html/sessions \
    && chmod -R 777 /var/www/html/sessions

# Update PHP session save path in Apache config
RUN echo "php_value session.save_path /var/www/html/sessions" >> /etc/apache2/apache2.conf

# Configure Apache to handle sessions
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
