# Smart Log Client

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
