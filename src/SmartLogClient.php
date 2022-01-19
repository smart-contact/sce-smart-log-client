<?php

namespace SmartContact\SmartLogClient;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class SmartLogClient {
    protected $application;
    
    public function __construct(protected Client $client, protected $applicationName)
    {
        $this->application = $this->getApp($applicationName);
    }

    /**
     * Return the application
     *
     * @param string $name
     * @return object
     */
    protected function getApp(string $name): object
    {
        try{
            // return $this->client->get("/apps?name={$name}")->getBody();
            return (object) [
                'id' => 1,
                'name' => 'Test App',
                'slug' => 'test-app',
                'shortName' => 'TA'
            ];
        }
        catch(ClientException $e){
            Log::emergency(
                "[smart-log-client error]: cannot create or get the application with name \"{$name}\".",
                [
                    'exception' => $e,
                ]
            );
        }
    }

    /**
     * create a unique id based on the application's short-name
     *
     * @return string
     */
    protected function generateLogUID(): string
    {
        $id = Str::uuid();
        return "{$this->application->shortName}-{$id}";
    }

    /**
     * Create a new application's log
     *
     * @param array $log
     * @return void
     */
    public function sendLog(array $log)
    {
        $log['id'] = $this->generateLogUID();

        // $log = [
        //     'id' => $this->generateLogUID(),
        //     'message' => $message,
        // ];

        try{
            // $res = $this->client->post("/apps/{$this->application->slug}/logs", [
            //     'json' => $log
            // ]);

            ddd($log);

            //handle no 200 responses
        }
        catch(ClientException $e){
            Log::emergency(
                "[smart-log-client error]: cannot insert log to app \"{$this->application->name}\"",
                [
                    'exception' => $e,
                    'log' => $log
                ]
            );
        }
    }
}
