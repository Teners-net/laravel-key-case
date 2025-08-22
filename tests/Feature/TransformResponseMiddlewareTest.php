<?php

namespace Teners\LaravelKeyCase\Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Teners\LaravelKeyCase\Http\Middleware\TransformResponseMiddleware;
use Teners\LaravelKeyCase\Tests\TestCase;

class TransformResponseMiddlewareTest extends TestCase
{
    private TransformResponseMiddleware $middleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = new TransformResponseMiddleware();
    }

    /**
     * Responce data keys are transformed
     */
    public function test_response_keys_are_transformed()
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

        $response = $this->middleware->handle($request, function () use ($testData) {
            return response()->json($testData);
        });

        $result = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('firstName', $result);
        $this->assertArrayHasKey('lastName', $result);

        $this->assertEquals('Emmanuel', $result['firstName']);
        $this->assertEquals('Adesina', $result['lastName']);
    }

    /**
     * Test that response transformation is ignored for specified routes.
     *
     * @return void
     */
    public function test_transform_request_middleware_ignores_specified_routes()
    {
        Config::set('key-case.ignore_response', ['api/ignore-this-route']);

        $request = Request::create(
            'api/ignore-this-route',
            'GET',
        );

        $expectedResponse = [
            'someKey' => 'someValue',
            'some_other_key' => "Some value",
            'SomeOtherKinds' => "SomeOtherKindsOfValue"
        ];

        $response = $this->middleware->handle($request, function () use ($expectedResponse) {
            return response()->json($expectedResponse);
        });

        $result = json_decode($response->getContent(), true);

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_response_middleware_handles_non_json()
    {
        $request = Request::create('/', 'GET');

        $response = $this->middleware->handle($request, function () {
            return response('<html><body>Not JSON</body></html>', 200, [
                'Content-Type' => 'text/html'
            ]);
        });

        $this->assertEquals('<html><body>Not JSON</body></html>', $response->getContent());
    }
}
