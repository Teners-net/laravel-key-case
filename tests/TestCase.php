<?php

namespace Teners\LaravelKeyCase\Tests;

use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

abstract class TestCase extends TestbenchTestCase
{

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('key-case', require __DIR__.'/../config/laravel-key-case.php');
    }
}
