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
use Throwable;
use Illuminate\Contracts\Cache\LockProvider;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Middleware
{
    /**
     * @var string
     */
    protected const LOG_MESSAGE_PREFIX = "CACHEWHOLEPAGE\t";
    
    /**
     * @var string
     */
    protected const DATA_GENERATION_SKIPPED = self::LOG_MESSAGE_PREFIX . 
        'Generation of data was skipped, data generated in another proccess';
    
    /**
     * @var int
     */
    protected const CACHE_TTL = 10;
    
    /**
     * @var int
     */
    protected const LOCK_TTL = 5;
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $excludes = (array) (config('cachewholepage.excludes') ?? []);
        $segment1 = $request->segment(1);
        
        if (in_array($segment1, $excludes) || !Auth::guest() || !$request->isMethod('GET')) {
            return $next($request);
        }

        $ttl = (int) (config('cachewholepage.cache_ttl') ?? self::CACHE_TTL);
        if (1 > $ttl) {
            return $next($request);
        }
        
        $key = md5($request->fullUrl());
        
        $value = Cache::get($key);
        if (null !== $value) {
            return response($value);
        }
        
        if (!(Cache::store()->getStore() instanceof LockProvider)) {
            $value = $next($request);
            Cache::put($key, $value->getContent(), $ttl);
            return $value;
        }
        
        $lockTtl = (int) (config('cachewholepage.lock_ttl') ?? self::LOCK_TTL);
        $lock = Cache::lock($key . '_lock', $lockTtl);
        try {
            if ($lock->block($lockTtl)) {
                $value = Cache::get($key);
                if (null !== $value) {
                    Log::info(self::DATA_GENERATION_SKIPPED);
                    return response($value);
                }
        
                $value = $next($request);
            }
        } catch (LockTimeoutException $e) {
            Log::notice(self::LOG_MESSAGE_PREFIX . "LockTimeoutException\t" . $e->getMessage());
            abort(503);
        } catch (Throwable $e) {
            Log::error(self::LOG_MESSAGE_PREFIX . "Throwable\t" . $e->getMessage());
            abort(500);
        } finally {
            $lock->release();
        }
        
        if (null !== $value) {
            Cache::put($key, $value->getContent(), $ttl);
        }
        
        return $value;
    }
}
