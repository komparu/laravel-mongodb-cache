<?php namespace Komparu\Mongodb\Cache;

use Illuminate\Cache\CacheManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class MongodbCacheServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
        $this->app->resolving('cache', function (CacheManager $cache) {
            $cache->extend('mongodb', function (Application $app) {
                return (new MongodbCacheManager($app))->driver('mongodb');
            });
        });
    }
}