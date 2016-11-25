# laravel-switchable-mail

该补丁可以使Laravel同时维护多个`Mail Driver`，且自动根据目标邮箱地址切换使用不同的`Mail Driver`。

## 说明

[English](README.md)

[中文简体](README.zh_CN.md)

## 安装

1. 使用`Composer`安装：

    ```sh
    composer require kevinzheng/laravel-switchable-mail
    ```

2. 在`config/app.php`中**替换**`Illuminate\Mail\MailServiceProvider::class`为`KVZ\Laravel\SwitchableMail\MailServiceProvider::class`。

3. 生成配置文件

    ```sh
    php artisan vendor:publish --tag=switchable-mail
    ```

## 配置

默认使用`.env`中配置的`MAIL_DRIVER`作为邮件发送服务商，如果有特定邮箱地址需要指定不同`Mail Driver`，可以在`switchable-mail.php`中进行配置。如果要使用`Laravel`未提供的邮件服务商驱动，需要同时安装对应驱动。比如阿里云`Direct Mail`和搜狐的`SendCloud`需要安装对应的支持才可以。

	```php
	// 'directmail' => [
	//     'qq.com', '163.com', '126.com', 'sina.com', 'sina.com.cn', 'sohu.com',
	// ],

	// 'mailgun' => [
	//     'example.com',
	// ],
	```

提供两个邮件驱动供大家参考选用：

[laravel-directmail](https://github.com/kevinzheng/laravel-directmail)

[laravel-sendcloud](https://github.com/kevinzheng/laravel-sendcloud)

## 使用

什么都不需要做，像使用`Laravel Mail`那样正常使用即可。

## 鸣谢

[ElfSundae](https://github.com/ElfSundae)
