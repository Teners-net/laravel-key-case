<?php

namespace Teners\LaravelKeyCase\Tests\Feature;

use Teners\LaravelKeyCase\Http\Middleware\TransformResponseMiddleware;
use Teners\LaravelKeyCase\Tests\TestCase;

class TransformResponseMiddlewareTest extends TestCase
{
    /**
     * Responce data keys are transformed
     */
    public function test_response_keys_Are_transformed()
    {
        $request = $this->makeRequest();

        $middleware  = new TransformResponseMiddleware();

        $response = $middleware->handle($request, function () {
            return response()->json($this->testData);
        });

        $result = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('first_name', $result);
        $this->assertArrayHasKey('last_name', $result);

        // Values are same
        $this->assertEquals('Emmanuel', $result['first_name']);
        $this->assertEquals('Adesina', $result['last_name']);
    }
}
