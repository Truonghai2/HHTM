# Sử dụng PHP 8.2 với CLI (chạy bằng php artisan serve)
FROM php:8.2-cli

# Cài các tiện ích và thư viện PHP cần thiết
RUN apt-get update && apt-get install -y \
    git unzip zip curl libzip-dev libpng-dev \
    libonig-dev libxml2-dev npm nodejs \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif bcmath

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Tạo thư mục làm việc
WORKDIR /var/www/html/httm

# Copy mã nguồn Laravel vào container
COPY . .

# Cài đặt PHP packages với Composer
RUN composer install --no-dev --optimize-autoloader

# Tạo cache Laravel
RUN php artisan config:clear && php artisan route:clear && php artisan view:clear

# Nếu có frontend Vite + Tailwind, build trước luôn (tuỳ chọn, bỏ qua nếu không dùng)
RUN npm install && npm run build

# Set ENV biến PORT (Render hoặc local sẽ dùng)
ENV PORT=8001

# Mở port
EXPOSE ${PORT}

# Chạy Laravel bằng artisan serve
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8001"]
