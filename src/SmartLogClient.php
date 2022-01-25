<?php

namespace SmartContact\SmartLogClient;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class SmartLogClient {
    protected $client;
    protected $application;
    
    public function __construct(Client $client, $applicationName)
    {   
        $this->client = $client;
        $this->application = $this->loadApplication($applicationName);
    }

    /**
     * Return the application data from smart-bridge app.
     *
     * @param string $name
     * @return object
     */
    protected function loadApplication($name)
    {
        try{
            $res = $this->client->get("/apps?name={$name}");
            return json_decode($res->getBody());
        }
        catch(ClientException $e){
            if($e->getCode() === 404){
                return $this->createApplication($name);
            }

            throw $e;
        }
    }

    /**
     * Creates a new application to smart-bridge-app.
     *
     * @param string $name
     * @return object
     */
    public function createApplication($name)
    {
        $res = $this->client->post('/apps', [
            'json' => ['name' => $name]
        ]);

        return json_decode($res->getBody());   
    }

    /**
     * get the application object
     *
     * @return object
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * create a unique id based on the application's short-name
     *
     * @return string
     */
    protected function generateLogUID()
    {
        $id = Str::random(32);
        return "{$this->application->shortName}-{$id}";
    }

    /**
     * Create a new application's log
     *
     * @param array $log
     * @return void
     */
    public function sendLog($log)
    {
        $log['incident_code'] = $this->generateLogUID();

        try{
            $this->client->post("/apps/{$this->application->slug}/logs", [
                'json' => $log
            ]);
        }
        catch(ClientException $e){
            Log::emergency(
                "[smart-log-client error]: cannot insert log to app \"{$this->application->name}\"",
                [
                    'exception' => $e,
                    'log' => $log
                ]
            );

            throw $e;
        }
    }
}
