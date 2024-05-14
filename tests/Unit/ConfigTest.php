<?php

namespace Teners\LaravelKeyCase\Tests\Unit;

use Teners\LaravelKeyCase\Tests\TestCase;

class ConfigTest extends TestCase
{
    public function test_can_get_config_values()
    {
        $config = config('key-case');

        $this->assertArrayHasKey('response_case', $config);
        $this->assertArrayHasKey('request_case', $config);
    }
}
