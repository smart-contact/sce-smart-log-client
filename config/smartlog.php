<?php

return [
  'apiBaseURL' => env('SMARTLOG_API_URL'),
  'applicationName' => env('SMARTLOG_APP_NAME'),
  'logLevel' => env('SMARTLOG_LEVEL', 'emergency'),
  'notificationChannel' => env('SMARTLOG_NOTIFICATION_CHANNEL')
];