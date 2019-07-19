# Ethic estibot
An Ethic web project for printers using Lumen and Vue.js

## Launch the project
composer install

npm i

Copy .env.example file, rename it into .env and enter database connection datas

php artisan migrate:fresh --seed

php -S localhost:8000 -t ./public
