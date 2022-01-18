# Smart Log Client
Smart Log Client è un package Laravel che mette in comunicazione il sistema SmartLog per monitorare e analizzare eventuali log.
Il client permette la registrazione di vari "livelli" di log come: 
    - `DEBUG`
    - `INFO`
    - `NOTICE`
    - `WARNING`
    - `ERROR`
    - `CRITICAL`
    - `ALERT`
    - `EMERGENCY`



## Installazione
```bash 
    composer require smart-contact/smart-log-client
```

> ⚠️⚠️⚠️ 
> 
> Se si usa Laravel <= 5.4 bisogna registrare il service provider manualmente.
>
>   ```php 
>       //'config/app.php'
>       <?php
>        
>       ...
>       'providers' => [
>          //...       
>          SmartContact\SmartLogClient\SmartLogClientServiceProvider::class
>       ],
>       ...
>   ```
>
>

## Configurazione
Il client necessita delle seguenti configurazioni: 

```env
    SMARTLOG_API_URL=https://smartlog.it
    SMARTLOG_APP_ID="Live Landing"
    SMARTLOG_LEVEL=alert
    SMARTLOG_NOTIFICATION_SERVICE=slack
    SMARTLOG_NOTIFICATION_LEVEL=error
```

### SMARTLOG_API_URL
Indica il dominio di smartlog a cui inviare i dati

### SMARTLOG_APP_ID

### SMARTLOG_LEVEL

### SMARTLOG_NOTIFICATION_SERVICE

### SMARTLOG_NOTIFICATION_LEVEL
### SMARTLOG_LEVEL_

## ExceptionHandler


## SmartLogClient Facade

### `SmartLogClient::log(string $message, string $level = debug)`
### `SmartLogClient::debug(string $message)`
### `SmartLogClient::info(string $message)`
### `SmartLogClient::notice(string $message)`
### `SmartLogClient::warning(string $message)`
### `SmartLogClient::error(string $message)`
### `SmartLogClient::critical(string $message)`
### `SmartLogClient::alert(string $message)`
### `SmartLogClient::emergency(string $message)`