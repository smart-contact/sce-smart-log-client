<?php

namespace SmartContact\SmartLogClient;

use GuzzleHttp\Client;

class SmartLogClient
{
    protected $application;
    const APPLICATIONS_PATH = '/api/applications';

    const JSON_HEADERS = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

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
        $res = $this->client->get(self::APPLICATIONS_PATH . '?name=' . $name, [
            'headers' => [
                ...self::JSON_HEADERS
            ]
        ]);

        $data = json_decode($res->getBody())->data;
        if (count($data) === 0) {
            return $this->createApplication($name);
        }

        return $data[0];
    }

    /**
     * Creates a new application to smart-bridge-app.
     *
     * @param string $name
     * @return object
     */
    public function createApplication(string $name)
    {
        $res = $this->client->post(self::APPLICATIONS_PATH, [
            'headers' => [
                ...self::JSON_HEADERS
            ],
            'json' => ['name' => $name]
        ]);

        $data = json_decode($res->getBody())->data;
        return $data;
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
     * Create a new application's log
     *
     * @param array $log
     * @return void
     */
    public function sendLog(array $log)
    {

        $res = $this->client->post(self::APPLICATIONS_PATH . "/{$this->application->slug}/logs", [
            'headers' => [
                ...self::JSON_HEADERS
            ],
            'json' => $log
        ]);

        $data = json_decode($res->getBody());

        return $data->incidentId;
    }
}