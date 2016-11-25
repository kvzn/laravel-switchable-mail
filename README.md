# laravel-switchable-mail
For Laravel to support multiple and switchable on runtime mail drivers.

## Installation

Run in project path:

```
composer require kevinzheng/laravel-switchable-mail
```

Add this line to providers in app.php:

```
KVZ\Laravel\SwitchableMail\MailServiceProvider::class,
```

## Configuration

Run in project path:

```
php artisan vendor:publish --tag=switchable-mail
```

Modify if you need:

```php
'default_driver' => env('MAIL_DRIVER', 'smtp'),
'drivers_division' => [
    //'directmail' => ['qq.com', '163.com', '126.com', 'sina.com', 'sina.com.cn', 'sohu.com'],
],
```

## Usage

Nothing to do, just use Laravel Mail as usual.

## Thanks to
[ElfSundae](https://github.com/ElfSundae)
