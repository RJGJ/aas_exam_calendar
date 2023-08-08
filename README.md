## api setup

```bash
$ cd api
$ composer i
$ cp .env.example .env
$ mysql -u root -p db_propan < db.sql -v
$ php artisan passport:install
$ php artisan storage:link

# for development
$ chmod 777 storage/ -R -v
$ php artisan serve

# for production
$ chown www-data:www-data /var/www/{build}/
$ chown www-data:www-data /var/www/{build}/propan/
$ chown -R www-data:www-data /var/www/{build}/propan/api/

$ find /var/www/{build}/propan/api/ -type f -exec chmod 644 {} \;
$ find /var/www/{build}/propan/api/ -type d -exec chmod 755 {} \;

$ chgrp -R www-data /var/www/{build}/propan/api/storage /var/www/{build}/propan/api/bootstrap/cache
$ chmod -R ug+rwx /var/www/{build}/propan/api/storage /var/www/{build}/propan/api/bootstrap/cache

# for development || production
$ composer dump-autoload
$ php artisan config:cache
```

## web & cms setup

```bash
$ cd web
$ npm i
$ cp .env.example .env

# for development
$ npm run dev

# for production
$ cp ecosystem.config.js.example ecosystem.config.js
$ npm run build && pm2 start
```