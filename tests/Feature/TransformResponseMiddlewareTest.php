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

        $this->assertArrayHasKey('firstName', $result);
        $this->assertArrayHasKey('lastName', $result);

        $this->assertEquals('Emmanuel', $result['firstName']);
        $this->assertEquals('Adesina', $result['lastName']);
    }
}
