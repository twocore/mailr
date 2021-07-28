# mailr

Swifter Mailer Bridge for Mako Framework

## Install

You can install the package through composer:

```php
composer require twocore/mailr
```

So now you can update your project with a single command.

```php
composer update
```

## Register Service

After installing you'll have to register the package in your ``app/config/application.php`` file:

```
'packages' =>
[
    ...
    'core' =>
    [
        ...
        twocore\mailr\MailrPackage::class,
    ]
    ...
],
```
## Info

Currently only the ``Swift_SmtpTransport`` is supported.