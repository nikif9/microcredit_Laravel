FROM   --platform=linux/amd64 php:8.2.12-fpm

# Устанавливаем необходимые системные зависимости
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath

# Копируем Composer из официального образа
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Копируем весь проект в контейнер (см. .dockerignore)
COPY . .

# Устанавливаем зависимости
RUN composer install --no-dev --optimize-autoloader

# (Опционально) Выполняем кэширование конфигов
RUN php artisan config:cache

CMD ["php-fpm"]
