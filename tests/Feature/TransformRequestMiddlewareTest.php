<?php

namespace Teners\LaravelKeyCase\Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Teners\LaravelKeyCase\Http\Middleware\TransformRequestMiddleware;
use Teners\LaravelKeyCase\Tests\TestCase;

class TransformRequestMiddlewareTest extends TestCase
{
    /**
     * Request parameter keys are transformed
     */
    public function test_request_keys_are_transformed_without_files()
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

        $middleware  = new TransformRequestMiddleware();

        $middleware->handle($request, function () use ($request) {
            $this->assertArrayHasKey('first_name', $request->all());
            $this->assertArrayHasKey('last_name', $request->all());

            return response()->json(['success' => true]);
        });
    }

    /**
     * Request parameter keys are transformed with file uploads
     */
    public function test_request_keys_are_transformed_with_files()
    {
        $testData = [
            'firstName' => "Emmanuel",
            'lastName' => "Adesina",
            'profilePicture' => UploadedFile::fake()->create('avatar.jpg', 100)
        ];

        $request = Request::create(
            "/",
            'POST',
            $testData,
        );

        $middleware  = new TransformRequestMiddleware();

        $middleware->handle($request, function () use ($request) {
            $this->assertArrayHasKey('first_name', $request->all());
            $this->assertArrayHasKey('last_name', $request->all());
            $this->assertArrayHasKey('profile_picture', $request->all());

            return response()->json(['success' => true]);
        });
    }
}
