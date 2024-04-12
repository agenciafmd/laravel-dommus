## Laravel - Dommus

[//]: # ([![Downloads]&#40;https://img.shields.io/packagist/dt/agenciafmd/laravel-dommus.svg?style=flat-square&#41;]&#40;https://packagist.org/packages/agenciafmd/laravel-dommus&#41;)
[![Licença](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

- Envia as conversões para a Dommus

## Instalação

```bash
composer require agenciafmd/laravel-dommus:dev-master
```

## Configuração

Para que a integração seja realizada, precisamos da **url da API**

Por isso, é necessário colocar o endereço no nosso .env

```dotenv
DOMMUS_URL=https://agenciafmd.secure.force.com/services/apexrest/LeadConnector
DOMMUS_TOKEN=seu_token
```


## Uso

Envie os campos no formato de array para o SendConversionsToDommus.

Para que o processo funcione pelos **jobs**, é preciso passar os valores dos cookies conforme mostrado abaixo.

```php
use Agenciafmd\Dommus\Jobs\SendConversionsToDommus;

    SendConversionsToDommus::dispatch([
        'url' => request()->headers->get('referer') ?? $_SERVER['HTTP_REFERER'],
        'name' => 'Carlos Seiji',
        'email' => 'carlos@fmd.ag',
        'phone' => '(17) 99999-9999',
        'campaign' => 'campaign',
        'medium' => 'medium',
        'source' => 'source',
        'property' => 'property',
    ])
    ->delay(5)
    ->onQueue('low');
```

Note que no nosso exemplo, enviamos o job para a fila **low**.

Certifique-se de estar rodando no seu queue:work esteja semelhante ao abaixo.

```shell
php artisan queue:work --tries=3 --delay=5 --timeout=60 --queue=high,default,low
```