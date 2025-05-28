<?php

namespace Agenciafmd\Dommus\Jobs;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class SendConversionsToDommus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly array $data = []) {}

    public function handle(): void
    {
        if (!config('laravel-dommus.token')) {
            return;
        }

        $client = $this->getClientRequest();

        $fullPhone = preg_replace('/\D/', '', $this->data['phone'] ?? '(99) 99999-9999');
        $ddd = substr($fullPhone, 0, 2);
        $phone = substr($fullPhone, 2);
        $fields = [
            'url' => $this->data['url'] ?? config('app.url'),
            'nome' => $this->data['name'] ?? 'John Doe',
            'ddd' => $ddd,
            'telefone' => $phone,
            'email' => $this->data['email'] ?? 'naoconfigurado@fmd.ag',
            'campanha' => $this->data['campaign'] ?? '',
            'midia' => $this->data['medium'] ?? 'Site',
            'origem' => $this->data['source'] ?? '',
            'empreendimento' => $this->data['property'] ?? 'Não configurado',
            'tags' => $this->data['tags'] ?? '',
        ];

        $client->post(config('laravel-dommus.token'), [
            'json' => $fields,
        ]);
    }

    private function getClientRequest(): Client
    {
        $logger = new Logger('Dommus');
        $logger->pushHandler(new StreamHandler(storage_path('logs/dommus-' . date('Y-m-d') . '.log')));

        $stack = HandlerStack::create();
        $stack->push(
            Middleware::log(
                $logger,
                new MessageFormatter('{method} {uri} HTTP/{version} {req_body} | RESPONSE: {code} - {res_body}')
            )
        );

        return new Client([
            'base_uri' => config('laravel-dommus.url'),
            'timeout' => 60,
            'connect_timeout' => 60,
            'http_errors' => false,
            'verify' => false,
            'handler' => $stack,
        ]);
    }
}
