<?php

namespace Agenciafmd\Dommus\Providers;

use Illuminate\Support\ServiceProvider;

class DommusServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        //
    }

    public function register(): void
    {
        $this->registerConfigs();
        $this->registerPublish();
    }

    private function registerPublish(): void
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-dommus.php' => base_path('config/laravel-dommus.php'),
        ], 'laravel-dommus:configs');
    }

    private function registerConfigs(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-dommus.php', 'laravel-dommus');
    }
}
