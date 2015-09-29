yii2 APNs through RabbitMQ
==========================
yii2 APNs through RabbitMQ extension

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist gbksoft/yii2-apns-through-rabbitmq "*"
```

or add

```
"gbksoft/yii2-apns-through-rabbitmq": "*"
```

to the require section of your `composer.json` file.


Usage
-----

To use this extension,  simply add the following code in your application configuration (console.php):

```php
'controllerMap' => [
    'apnsGcm' => [
        'class' => 'gbksoft\console\ApnsGcmController',
    ],
],