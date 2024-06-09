<?php

namespace Teners\LaravelKeyCase\Tests\Feature;

use Illuminate\Http\Request;
use Teners\LaravelKeyCase\Http\Middleware\TransformResponseMiddleware;
use Teners\LaravelKeyCase\Tests\TestCase;

class TransformResponseMiddlewareTest extends TestCase
{
    /**
     * Responce data keys are transformed
     */
    public function test_response_keys_Are_transformed()
    {
        $testData = [
            'firstName' => "Emmanuel",
            'lastName' => "Adesina",
        ];

        $request = Request::create(
            "/",
            'GET',
            $testData
        );

        $middleware  = new TransformResponseMiddleware();

        $response = $middleware->handle($request, function () use ($testData) {
            return response()->json($testData);
        });

        $result = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('firstName', $result);
        $this->assertArrayHasKey('lastName', $result);

        $this->assertEquals('Emmanuel', $result['firstName']);
        $this->assertEquals('Adesina', $result['lastName']);
    }
}
