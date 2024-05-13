<?php

namespace Teners\LaravelKeyCase\Tests;

use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

abstract class TestCase extends TestbenchTestCase
{
    public $testData = [
        'firstName' => "Emmanuel",
        'lastName' => "Adesina",
    ];

    public function makeRequest(): Request
    {
        return Request::create(
            "/",
            'GET',
            $this->testData
        );
    }
}
