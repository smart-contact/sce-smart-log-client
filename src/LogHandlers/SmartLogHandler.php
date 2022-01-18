<?php

namespace SmartContact\SmartLogClient\LogHandlers;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use SmartContact\SmartLogClient\SmartLogClient;

class SmartLogHandler extends AbstractProcessingHandler{

    public function __construct(private SmartLogClient $smartlogClient, $level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(array $record): void
    {
        //process here the monolog record
        $this->smartlogClient->sendLog([
            'message' => $record['message'],
            'error' => [
                'code' => $record['context']['exception']->getCode(),
                'type' => $record['level_name']
            ],
            'traceFormatted' => $record['context']['exception']->getTraceAsString()
        ]);
    }
}
