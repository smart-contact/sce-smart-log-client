<?php

namespace SmartContact\SmartLogClient\Exceptions;

use Throwable;
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
    public function report(Throwable $e)
    {
        
        // switch($e->getCode()){
        //     case LOG::ERROR:

        //         break;
        // }

        Log::info($e->getMessage(), [
            'exception' => $e
        ]);
    }

}
