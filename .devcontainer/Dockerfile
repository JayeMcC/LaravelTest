FROM my-dev-environment:latest

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=Australia/Perth

# Set the working directory for your app
WORKDIR /var/www/html

# Install system dependencies, PHP extensions, and supervisor
RUN apt-get update && apt-get install -y \
    software-properties-common \
    curl \
    zip \
    unzip \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    supervisor \
    && add-apt-repository ppa:ondrej/php -y \
    && apt-get update && apt-get install -y \
    php8.2 \
    php8.2-cli \
    php8.2-fpm \
    php8.2-mysql \
    php8.2-zip \
    php8.2-mbstring \
    php8.2-xml \
    php8.2-curl \
    php8.2-gd \
    php8.2-intl \
    php8.2-bcmath \
    php8.2-soap \
    && apt-get clean

# Manually configure and enable GD extension
RUN echo "extension=gd" > /etc/php/8.2/cli/conf.d/20-gd.ini && \
    echo "extension=gd" > /etc/php/8.2/fpm/conf.d/20-gd.ini

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the repository contents into the container's working directory
COPY . /var/www/html

# Set the working directory to the project's root
WORKDIR /var/www/html

# Run Composer within the working directory
RUN composer install --prefer-dist --no-interaction --optimize-autoloader

# Set folder permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Configure supervisor to manage the Laravel queue worker
RUN mkdir -p /etc/supervisor/conf.d
COPY ./supervisord.conf /etc/supervisor/conf.d/laravel-worker.conf

# Expose port 8000 for Laravel's built-in server
EXPOSE 8000

# Start Supervisor to manage Laravel server and queue worker
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
