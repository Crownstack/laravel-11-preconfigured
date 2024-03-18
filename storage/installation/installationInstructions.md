Prerequisite to install a laravel project:-
1. Install PHP 8.2 or above
2. Install composer 

Install Laravel:-
* Installing all the package dependecies
    - composer install
* Give directories permissions:
    - sudo chmod -R 777 storage
    - sudo chmod -R 777 bootstrap/cache
* Make a copy of .env.example file and rename it to .env file
* Create database connection inside .env file.
* Create two virtual hosts,one for UI and another for accessing Api.
* Generating the application's key
    - php artisan key:generate