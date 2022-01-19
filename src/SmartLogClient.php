<?php

namespace SmartContact\SmartLogClient;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class SmartLogClient {
    protected $application;
    
    public function __construct(protected Client $client, protected string $applicationName)
    {
        $this->application = $this->loadApplication($applicationName);
    }

    /**
     * Return the application data from smart-bridge app.
     *
     * @param string $name
     * @return object
     */
    protected function loadApplication(string $name)
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
    public function createApplication(string $name)
    {
        $res = $this->client->post('/apps', [
            'name' => $name
        ]);

        return json_decode($res->getBody());   
    }

    /**
     * get the application object
     *
     * @return object
     */
    public function getApplication(): object
    {
        return $this->application;
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
        $log['incident_code'] = $this->generateLogUID();
        $log['referer'] = request()->headers->get('referer');
        $log['ip'] = request()->ip();
        $log['user'] = auth()->user()?->id;

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
