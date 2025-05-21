FROM php:8.1-fpm-alpine

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Set working directory
WORKDIR /var/www/html

# Copy the PHP application code to the container
COPY /src/ /var/www/html/

EXPOSE 9000

CMD ["php-fpm", "-F"]