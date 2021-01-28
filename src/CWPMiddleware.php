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

use Closure;
use Cache;

class CWPMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ttl = (int) config('cachewholepage.ttl');
        if (1 > $ttl) {
            return $next($request);
        }
        
        $key = $request->fullUrl();
        if (Cache::has($key)) {
            return response(Cache::get($key));
        }
        
        $response = $next($request);
        
        Cache::put($key, $response->getContent(), $ttl);
        
        return $response;
    }
}
