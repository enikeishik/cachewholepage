# CacheWholePage

Package for [Laravel framework](https://laravel.com/) - 
allow to cache whole page output.

## Requirements

*   PHP >= 7.4
*   Laravel >= 7.0

## Install

Install (or update) package via [composer](http://getcomposer.org/):

```bash
composer require enikeishik/cachewholepage
```

Make sure autoload will be changed:

```bash
composer dump-autoload
```

Publish package via artisan:

```bash
php artisan vendor:publish --provider="Enikeishik\CacheWholePage\ServiceProvider"
```

This command copy configuration file into corresponding project folder.

## After install

By default caching apply to all routes in `web` group. It can be changed in service provider.

Tune configuration parameter `CACHE_WP_TTL` in `env` file 
(or `ttl` parameter in `cachewholepage.php` file in project configuration folder) 
corresponding to your needs.

Add first segment of path to `excludes` array in `cachewholepage.php` file 
in project configuration folder to avoid caching.

Cache and lock TTLs should be obviously more than the estimated page generation time.
