# 📃Smart Log Client
Smart Log Client è un package Laravel che mette in comunicazione il sistema SmartLog per monitorare e analizzare eventuali log.

## Installazione
```bash 
composer require smart-contact/smart-log-client
```

## Configurazione
Il client necessita delle seguenti variabili env: 

```env
SMARTLOG_API_URL=https://smartlog.it
SMARTLOG_APPLICATION_NAME="Live Landing"
SMARTLOG_LEVEL=emergency
SMARTLOG_NOTIFICATION_SERVICE=slack
```

| var | default | description |
|-----|---------|-------------|
| SMARTLOG_API_URL | | Indica il dominio di smartlog a cui inviare i dati |
| SMARTLOG_APPLICATION_NAME | | Nome dell'applicazione in uso. <br>Deve corrispondere al nome presente sull'app Smart Log, se non esiste verrà creata una nuova applicazione con il nome fornito. |
| SMARTLOG_LEVEL | `"emergency"` | Indica il livello di log da notificare a smart log |
| SMARTLOG_NOTIFICATION_SERVICE | `"slack"` | Servizio di notifica, se vuoto non viene incluso. Es. slack, teams ecc |


## Utilizzo
Per utilizzare SmartLog basta settare la variabile d'ambiente `LOG_CHANNEL` a `smartlog-stack`.
Il channel **smartlog-stack** include oltre a smartlog un channel per l'invio delle notifiche a un servizio di messagistica (slack, teams ecc),
che si può settare usando al variabile d'ambiente `SMARTLOG_NOTIFICATION_CHANNEL`

## ExceptionHandler
Per catturare in automatico tutte le eccezioni che l'applicazione laravel genera è necessario modificare il file `app/Exceptions/Handler.php`, 
facendo estendere la classe `SmartLogClientException`.

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


Bisogna estendere la classe `SmartContact\SmartLogClient\Exceptions\SmartLogClientException`

## Invio di un log manualmente
Per inviare un log in modo manuale bisogna usare la Facade Log con i metodi che mette a disposizione.

```php
    //Invia un log con livello Error.
    Log::error('Error message', [...]);
```

## Scelta del servizio di notifiche
Laravel di default include il channel Monolog di **slack**, 
per utilizzarlo insieme all'invio a smart log bisogna settare la variabile `SMARTLOG_NOTIFICATION_SERVICE` con il nome del channel scelto, in questo caso `slack`.

Se invece si vuole usare un servizio diverso basterà creare il channel e usarlo. 

Es: Utilizzo di Microsoft [teams](https://github.com/cmdisp/monolog-microsoft-teams)

```php
<?php
// config/logging.php

return [
    //...

    'channels' => [
        //...

        'teams' => [
            'driver' => 'custom',
            'via' => \CMDISP\MonologMicrosoftTeams\TeamsLogChannel::class,
            'level' => 'error',
            'url' => 'INCOMING_WEBHOOK_URL',
        ]
    ]
]


```

```env
# .env
LOG_CHANNEL=smartlog-stack
SMARTLOG_NOTIFICATION_CHANNEL=teams
```
