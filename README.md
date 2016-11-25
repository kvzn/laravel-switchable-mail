# laravel-switchable-mail
For Laravel to support multiple and switchable on runtime mail drivers.

## Installation

1. Install this package using the Composer manager:
  ```sh
  composer require kevinzheng/laravel-switchable-mail
  ```
2. **Replace** `Illuminate\Mail\MailServiceProvider::class` with `KVZ\Laravel\SwitchableMail\MailServiceProvider::class` in the `config/app.php` file.
3. Publish configuration file: 
  ```sh
  php artisan vendor:publish --tag=switchable-mail
  ```

## Configuration

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
