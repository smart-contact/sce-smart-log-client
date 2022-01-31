<?php

namespace SmartContact\SmartLogClient\LogHandlers;

use Illuminate\Support\Facades\URL;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use SmartContact\SmartLogClient\SmartLogClient;

class SmartLogHandler extends AbstractProcessingHandler
{

    public function __construct(private SmartLogClient $smartlogClient, $level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(array $record): void
    {
        $exception = array_key_exists('exception', $record['context']) ? $record['context']['exception'] : null;
        if ($exception) {
            unset($record['context']['exception']);
        }

        //process here the monolog record
        $this->smartlogClient->sendLog([
            'referer' => URL::current(),
            'ip' => request()->ip(),
            'user_id' => auth()->id(),
            'message' => $record['message'],
            'status_code' => $exception ? $exception->getCode() : 0,
            'level_name' => $record['level_name'],
            'level_code' => $record['level'],
            'context' => count($record['context']) !== 0 ? json_encode($record['context']) : null,
            'formatted' => $exception ? $exception->getTraceAsString() : $record['formatted']
        ]);
    }
}