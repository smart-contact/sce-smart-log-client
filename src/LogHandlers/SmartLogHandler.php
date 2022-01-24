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

        $exception = $record['context']['exception'];
        //process here the monolog record
        $this->smartlogClient->sendLog([
            'referer' => request()->headers->get('referer'),
            'ip' => request()->ip(),
            'user' => auth()->user() ? auth()->user()->id : null,
            'message' => $record['message'],
            'status_code' => $exception->getCode(),
            'level_name' => $record['level_name'],
            'level_code' => $record['level'],
            'context' => $record['context'],
            'formatted' => $exception->getTraceAsString()
        ]);
    }
}
