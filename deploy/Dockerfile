FROM php:8.2-fpm

# Copy composer.lock and composer.json into the working directory
COPY composer.lock composer.json /var/www/html/httm/

# Set working directory
WORKDIR /var/www/html/httm/

# Install dependencies for the operating system software
RUN apt-get update && apt-get install -y \
    nginx \
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
COPY . /var/www/html/httm
ADD ./deploy/custom_php.ini /usr/local/etc/php/conf.d/custom_php.ini

# Copy nginx configuration
COPY ./deploy/nginx.conf /etc/nginx/conf.d/default.conf

# Create necessary directories and set permissions
RUN mkdir -p /var/www/html/httm/api-docs

# Assign permissions of the working directory to the www-data user
RUN chown -R www-data:www-data \
        /var/www/html/httm/storage \
        /var/www/html/httm/bootstrap/cache

RUN chown -R www-data:www-data /var/www/html/httm/storage \
    && chmod -R 775 /var/www/html/httm/storage \
    && chmod -R 775 /var/www/html/httm/bootstrap/cache

# Install composer (php package manager)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update && apt-get install -y nodejs npm

COPY ./deploy/php-fpm.conf /usr/local/etc/php-fpm.d/zzz-custom.conf

COPY ./deploy/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PORT=8080

# Expose port 9000 and start php-fpm server (for FastCGI Process Manager)
EXPOSE 8080
ENTRYPOINT ["/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
