<?php

namespace Teners\LaravelKeyCase;

use Illuminate\Support\ServiceProvider;

class LaravelKeyCaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerResources();
        $this->publishResources();
    }

    public function register()
    {
        if (app()->runningInConsole()) {
        }
    }

    private function registerResources()
    {
    }

    private function publishResources()
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-key-case.php' => config_path('key-case.php'),
        ], 'key-case-config');
    }
}
