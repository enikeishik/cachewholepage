<?php
/**
 * CacheWholePage package for Laravel framework.
 * 
 * @copyright   Copyright (C) 2021 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

declare(strict_types=1);

namespace Enikeishik\CacheWholePage;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config' => config_path(),
        ], 'config');
        
        $this->app['router']->aliasMiddleware('cachewholepage', \Enikeishik\CacheWholePage\Middleware::class);
        $this->app['router']->pushMiddlewareToGroup('web', \Enikeishik\CacheWholePage\Middleware::class);
    }
}
