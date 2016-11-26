# laravel-switchable-mail

It makes `Laravel` be able to maintain multiple mail drivers at the same time, and be able to send to different mail addresses with specified mail drivers as configured at runtime automatically.

## Instructions

[English](README.md)

[中文简体](README.zh_CN.md)

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

It uses the `MAIL_DRIVER` configured in `.env` as the default mail driver, for those addresses you want to send to with specified mail drivers, configure it at `switchable-mail.php`. 
You should install the mail drivers not provided by `Laravel` at the meantime, such as Aliyun `Direct Mail` and `SendCloud`:

	```php
	// 'directmail' => [
	//     'qq.com', '163.com', '126.com', 'sina.com', 'sina.com.cn', 'sohu.com',
	// ],

	// 'mailgun' => [
	//     'example.com',
	// ],
	```

Two mail drivers are available:

[laravel-directmail](https://github.com/kevinzheng/laravel-directmail)

[laravel-sendcloud](https://github.com/kevinzheng/laravel-sendcloud)

## Usage

Nothing needs to be done, just use `Laravel Mail` as usual.

## Thanks to
[ElfSundae](https://github.com/ElfSundae)
