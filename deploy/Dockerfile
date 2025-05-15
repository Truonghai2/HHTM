FROM php:8.1-fpm

# Copy composer.lock and composer.json into the working directory
COPY composer.lock composer.json /var/www/html/device_management/

# Set working directory
WORKDIR /var/www/html/device_management/

# Install dependencies for the operating system software
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libxml2-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libmcrypt-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    nano \
    libzip-dev \
    unzip \
    git \
    libonig-dev \
    curl \
    gnupg2 \
    supervisor

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Config supervisor
RUN mkdir -p "/var/log/supervisor"
COPY ./deploy/supervisord.conf /etc/supervisor/supervisord.conf

# Install extensions for php
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl xml bcmath
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Copy existing application directory contents to the working directory
COPY . /var/www/html/device_management
ADD ./deploy/custom_php.ini /usr/local/etc/php/conf.d/custom_php.ini

# Create necessary directories and set permissions
RUN mkdir -p /var/www/html/device_management/api-docs

# Assign permissions of the working directory to the www-data user
RUN chown -R www-data:www-data \
        /var/www/html/device_management/storage \
        /var/www/html/device_management/bootstrap/cache

RUN chmod -R 775 /var/www/html/device_management/storage \
        && chmod -R 775 /var/www/html/device_management/api-docs

# Install composer (php package manager)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update && apt-get install -y nodejs npm

ENV COMPOSER_ALLOW_SUPERUSER=1

# Expose port 9000 and start php-fpm server (for FastCGI Process Manager)
EXPOSE 9000
CMD ["/usr/bin/supervisord"]
