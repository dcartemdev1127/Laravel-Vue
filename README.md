
# Find A Super Tutor V. 1.0 (Laravel 11.0) 

## Install Instruction
```
cd /var/www
clone repo
cd webapp
cp .env.example .env                        // update DB credentials and other settings
composer install                            // on development system only!
composer install --prefer-dist --no-dev -o  // on live system only!
```

## Update Instruction
```
cd /var/www
git pull https://github.com/FindaSuperTutor/webapp.git
cd webapp
cp .env.example .env        // update DB credentials and other settings
composer update

php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan key:generate
```

## Install Cron Job

```
crontab -e
```

And put this cron job

```
*/5 * * * * php /var/www/webapp/artisan schedule:run >> /var/www/webapp/storage/logs/cron.log 2>&1
```

### vue upgrade
```
cd /var/www/order10
npm update -g @vue/cli // install vue command as global
vue upgrade
npm run build
```

#### DB
Local MySQL/Maria DB with all data expect customer data


## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## Server Requirements
PHP version 8.2 or higher is required, with the following extensions installed: