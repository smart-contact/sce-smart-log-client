<?php

namespace SmartContact\SmartLogClient\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Exceptions\Handler;

class SmartLogClientException extends Handler
{

    /**
     * Report or log an exception.
     *
     * @param Throwable $e
     * @return void
     */
    public function report(Exception $e)
    {
        Log::critical($e->getMessage(), [
            'exception' => $e
        ]);
    }

}
