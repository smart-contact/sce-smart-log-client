<?php

namespace SmartContact\SmartLogClient;

use Illuminate\Support\ServiceProvider;
use SmartContact\SmartLogClient\SmartLogClient;

class SmartLogClientServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/logging.php', 'logging');
        $this->app->singleton(SmartLogClient::class, fn() => new SmartLogClient);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Merge recursively (multi-dimensional array ver) the given configuration with the existing configuration.
     *
     * see: https://medium.com/@alkhwlani/why-not-use-build-in-function-array-merge-recursive-53ee8eee151c
     * https://medium.com/@alkhwlani/why-not-use-build-in-function-array-merge-recursive-53ee8eee151c
     *
     * @param string $path
     * @param string $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);
        $this->app['config']->set($key, array_merge_recursive($config, require $path));
    }
}
