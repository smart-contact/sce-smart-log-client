# 📃Smart Log Client
Smart Log Client è un package Laravel che mette in comunicazione il sistema SmartLog per monitorare e analizzare eventuali log.
Il pacchetto utilizza la libreria Monolog, già integrata in Laravel.

## Installazione
```bash 
composer require smart-contact/smart-log-client
```

## Publish config file
``` bash
php artisan vendor:publish --tag="smartlog-client-config"
```

## Configurazione Client
Il client necessita delle seguenti variabili env: 

```env
SMARTLOG_API_URL=https://smartlog.it
SMARTLOG_APPLICATION_NAME="Live Landing"
```

| var | default | description |
|-----|---------|-------------|
| SMARTLOG_API_URL | | Indica il dominio di smartlog a cui inviare i dati |
| SMARTLOG_APPLICATION_NAME | | Nome dell'applicazione in uso. <br>Deve corrispondere al nome presente sull'app Smart Log, se non esiste verrà creata una nuova applicazione con il nome fornito. |

## Configurazione Laravel Logging
Aggiungere il seguente codice nel file `config/logging.php`
```php

return [
    //...

    'channels' => [
        //...
        'smartlog' => [
            'driver' => 'monolog',
            'handler' => \SmartContact\SmartLogClient\LogHandlers\SmartLogHandler::class,
            'level' => config('smartlog.logLevel')
        ]
    ]
];
```

## Utilizzo

### Channel singolo
Impostare la variabile di ambiente LOG_CHANNEL a `smartlog`

```
// .env
LOG_CHANNEL=smartlog
```

### Channel multiplo (stack)
Aggiungere ai channels, il channel 'smartlog`

```php
...

'stack' => [
    'driver' => 'stack',
    'channels' => ['single', 'smartlog'],
    'ignore_exceptions' => false,
],

...
```
e settare la variabile LOG_CHANNEL
```
// .env
LOG_CHANNEL=stack
```

## Generazione automatica dei log 

Per generare dei log automatici dalle eccezioni lanciate da Laravel,
bisogna modificare il file `app/Exceptions/Handler.php`.
La classe `Handler` dovrà estendere la classe `SmartLogClientException`

Es.

```php
// app/Exceptions/Handler.php

<?php

namespace App\Exceptions;

use Throwable;
use SmartContact\SmartLogClient\Exceptions\SmartLogClientException;

class Handler extends SmartLogClientException{
    //...
}

```
