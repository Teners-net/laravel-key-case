<?php

namespace Teners\LaravelKeyCase\Tests\Feature;

use Teners\LaravelKeyCase\Http\Middleware\TransformRequestMiddleware;
use Teners\LaravelKeyCase\Tests\TestCase;

class TransformRequestMiddlewareTest extends TestCase
{
    /**
     * Request parameter keys are transformed
     */
    public function test_request_keys_are_transformed()
    {
        $request = $this->makeRequest();

        $middleware  = new TransformRequestMiddleware();

        $middleware->handle($request, function () use ($request) {
            $this->assertArrayHasKey('first_name', $request->all());
            $this->assertArrayHasKey('last_name', $request->all());

            return response()->json(['success' => true]);
        });
    }
}
