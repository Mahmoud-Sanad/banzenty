cd /var/www

composer install

php artisan migrate --seed

php-fpm
