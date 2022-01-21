<?php

namespace SmartContact\SmartLogClient;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;
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
        $this->publishes([
            __DIR__.'/../config/smartlog.php' => config_path('smartlog.php')
        ]);

        $this->app->singleton(SmartLogClient::class, function(){
            $httpClient = new Client([
                'base_uri' => config('smartlog.apiURL')
            ]);
            
            $applicationName = config('smartlog.applicationName');
            return new SmartLogClient($httpClient, $applicationName);
        });
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
