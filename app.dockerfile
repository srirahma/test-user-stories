FROM php:8.0.2-fpm

ENV \
    APP_DIR ="/var/www"
    
COPY . $APP_DIR
COPY .env.example $APP_DIR/.env
COPY composer.json $APP_DIR/composer.json

RUN docker-php-ext-install pdo pdo_mysql

# RUN curl -sS https://getcomposer.org/installer | php -- \
#     --install-dir=/usr/bin --filename=composer

# RUN cd $APP_DIR && composer update
# RUN cd $APP_DIR && php artisan key:generate

WORKDIR $APP_DIR
