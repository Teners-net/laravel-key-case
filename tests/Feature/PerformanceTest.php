<?php

namespace Teners\LaravelKeyCase\Tests\Unit;

use Illuminate\Http\Request;
use Teners\LaravelKeyCase\Services\KeyTransformer;
use Teners\LaravelKeyCase\Http\Middleware\TransformRequestMiddleware;
use Teners\LaravelKeyCase\Tests\TestCase;

class PerformanceTest extends TestCase
{
    public function test_deep_nesting_protection()
    {
        $transformer = new KeyTransformer('camel', 3);

        $deepData = [
            'level_1' => [
                'level_2' => [
                    'level_3' => [
                        'level_4' => [
                            'level_5' => 'too deep'
                        ]
                    ]
                ]
            ]
        ];

        $result = $transformer->convertKeys($deepData);

        $this->assertArrayHasKey('level1', $result);
        $this->assertArrayHasKey('level2', $result['level1']);
        $this->assertArrayHasKey('level3', $result['level1']['level2']);

        $this->assertArrayHasKey('level_4', $result['level1']['level2']['level3']);
    }

    public function test_large_dataset_performance()
    {
        $transformer = new KeyTransformer('camel');

        $largeData = [];
        for ($i = 0; $i < 2000; $i++) {
            $largeData["test_key_$i"] = "value_$i";
        }

        $startTime = microtime(true);
        $result = $transformer->convertKeys($largeData);
        $endTime = microtime(true);

        $processingTime = $endTime - $startTime;

        // Should complete within reasonable time (less than 1 second)
        $this->assertLessThan(1.0, $processingTime);

        $this->assertEquals(2000, count($result));
        $this->assertArrayHasKey('testKey0', $result);
        $this->assertArrayHasKey('testKey1999', $result);
    }

    public function test_middleware_skips_empty_requests()
    {
        $middleware = new TransformRequestMiddleware();
        $request = Request::create('/', 'GET');

        $processed = false;

        $middleware->handle($request, function ($req) use (&$processed) {
            $processed = true;
            return response()->json(['success' => true]);
        });

        $this->assertTrue($processed);
        $this->assertEquals([], $request->all());
    }

    public function test_concurrent_transformer_instances()
    {
        $transformers = [];
        $results = [];

        // Create multiple transformer instances
        for ($i = 0; $i < 5; $i++) {
            $transformers[$i] = new KeyTransformer('camel');
        }

        // Use them concurrently
        for ($i = 0; $i < 5; $i++) {
            $data = ["test_key_$i" => "value_$i"];
            $results[$i] = $transformers[$i]->convertKeys($data);
        }

        for ($i = 0; $i < 5; $i++) {
            $this->assertArrayHasKey("testKey$i", $results[$i]);
        }
    }
}
