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
        if($this->app->runningInConsole()){
            $this->publishes([
                __DIR__.'/../config/smartlog.php' => config_path('smartlog.php')
            ], 'smartlog-configs');
        }
    }
}
