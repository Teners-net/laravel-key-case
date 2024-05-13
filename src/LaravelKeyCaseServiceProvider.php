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
    }
}
