## Laravel - Dommus

[![Downloads](https://img.shields.io/packagist/dt/agenciafmd/laravel-dommus.svg?style=flat-square)](https://packagist.org/packages/agenciafmd/laravel-dommus)
[![Licença](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

- Envia as conversões para a Dommus

## Instalação

```bash
composer require agenciafmd/laravel-dommus:v11.x-dev
```

## Configuração

Para que a integração seja realizada, precisamos da **url da API**

Por isso, é necessário colocar o endereço no nosso .env

```dotenv
DOMMUS_URL=https://api.leads.dommus.com.br/webhook/forms/
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