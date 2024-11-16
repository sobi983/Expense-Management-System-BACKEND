# Use the official PHP image
FROM php:8.2-cli

# Set the working directory
WORKDIR /var/www/html

# Copy your application files
COPY . .

# Copy keys into the container
COPY keys /var/www/html/keys

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Install any required PHP extensions (if needed)
RUN docker-php-ext-install pdo pdo_mysql

# Expose the port for the built-in server
EXPOSE 8000

# Start the PHP server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www/html"]

