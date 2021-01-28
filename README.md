# CacheWholePage

Package for [Laravel framework](https://laravel.com/) - 
allow to cache whole page output.

## Requirements

*   PHP >= 7.4
*   Laravel >= 7.0

## Install

Install (or update) package via [composer](http://getcomposer.org/):

```bash
composer install enikeishik/cachewholepage
```

Make sure autoload will be changed:

```bash
composer dump-autoload
```

Publish package via artisan:

```bash
php artisan vendor:publish --provider="Enikeishik\CacheWholePage\CWPServiceProvider"
```

This command copy configuration file into corresponding project folder.

## After install

Tune configuration parameter `ttl` corresponding to your needs 
(check cachewholepage.php file in project configuration folder).
