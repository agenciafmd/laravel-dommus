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
        $this->loadConfigs();
        $this->publish();
    }

    protected function publish(): void
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-dommus.php' => base_path('config/laravel-dommus.php'),
        ], 'laravel-dommus:configs');
    }

    protected function loadConfigs(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-dommus.php', 'laravel-dommus');
    }
}
