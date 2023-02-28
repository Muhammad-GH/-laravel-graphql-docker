FROM php:7.4-fpm
RUN apt-get update && apt-get install -y git curl libpng-dev libonig-dev libxml2-dev zip unzip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd
WORKDIR /app
COPY composer.json .
RUN composer install --no-scripts
COPY . .
CMD php artisan serve --host=0.0.0.0 --port 80

## host 0.0.0.0 is important with port 80 for Fargate
## Make sure Fargate service security group allows all traffic for IPv8
