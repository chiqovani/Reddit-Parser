### Installation instruction

clone repository to server : 
```bash
git clone git@github.com:nikopeikrishvili/reddit_parser.git
```

Install dependencies for laravel:
```bash
cd reddit_parser
composer install
```
Generate Key 
```bash
php artisan key:generate
```
change database config in .env file to user mysql database
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

####Generate database schema 
```
php artisan migrate
```
####Run parsing command via console
```
php artisan reddit:parse
```
####Running Parser via cron job
```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```
You can edit running frequency in app/Console/Kernel.php file 

here is a link of available options : https://laravel.com/docs/8.x/scheduling#schedule-frequency-options
```php
$schedule->command('reddit:parse')->everyTenMinutes();
```
https://laravel.com/docs/8.x/scheduling#schedule-frequency-options

#### Adding reddit's or posts to database
you can add reddit or post urls for parsing via web interface of application, in this case you need to place project 
inside html root of server and navigate to URL via browser.


