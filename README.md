# Pre-requisite software

Make sure to have the following software installed before installing Laravel:

## PHP 8.3 and all the required PHP packages

```
sudo apt install curl zip unzip php8.3 php8.3-fpm php8.3-mysql php8.3-cli php8.3-mbstring php8.3-zip php8.3-xml php8.3-curl php8.3-dom php8.3-gd
```

## Composer 2

```
curl -sS https://getcomposer.org/installer -o composer-setup.php
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php
```

## Nginx

```
sudo apt install nginx
```

# Installing the Laravel Project

## Setup Virtual Host on NGINX

Sample NGINX virtual host configuration file:
```
server {
    listen 80;
    root /project-root-folder/public;
    index index.php;
    server_name api.example.com;

    #Control the max body size client can upload in a single HTTP request
    client_max_body_size 999m;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
    }
}
```

## Install dependencies

```
composer install
```

## Give folder permissions to directories

```
sudo chmod -R 777 storage/logs
sudo chmod -R 777 bootstrap/cache
```

## Create .env file and set it up

```
cp .env.example .env
```

## Generate application key

```
php artisan key:generate
```


# Other useful commands

## Caching the application configuration for faster load for production environment

To give application a speed boost, you should cache all of your configuration files into a single file. This will combine all of the configuration options for your application into a single file which can be quickly loaded by the framework.

### Generating config's cache

```
php artisan config:cache
```

You should typically run the above command as part of your production deployment process. The command should not be run during local development as configuration options will frequently need to be changed during the course of your application's development.

[Reference reading](https://laravel.com/docs/11.x/configuration#configuration-caching)

### Deleting (purging) config's cache

```
php artisan config:clear
```