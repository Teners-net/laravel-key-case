<?php

namespace Teners\LaravelKeyCase\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use InvalidArgumentException;

class KeyTransformer
{
    private const CASES = [
        'camel',
        'snake',
        'kebab',
        'studly',
        'lower',
        'upper',
        'ucfirst',
        'ucwords',
        'singular',
        'plural',
        'slug',
        'title',
    ];

    private int $maxDepth = 10;

    public function __construct(private string $case, ?int $maxDepth = null)
    {
        if (!in_array($case, self::CASES)) {
            throw new InvalidArgumentException('Invalid case: ' . $case);
        }

        $this->case = $case;
        $this->maxDepth = $maxDepth ?? config('key-case.max_depth', 10);
    }

    /**
     * Convert keys with caching for performance
     */
    private function transformKey(string $key): string
    {
        $transformedKey = Str::{$this->case}($key);

        return $transformedKey;
    }

    /**
     * Check if value should be skipped
     */
    private function shouldSkipValue($value): bool
    {
        return $value instanceof UploadedFile
            || is_resource($value)
            || (is_object($value) && method_exists($value, '__toString') === false && !$this->isPlainObject($value));
    }

    /**
     * Check if object is a plain object (stdClass or similar)
     */
    private function isPlainObject($value): bool
    {
        return is_object($value) && (
            $value instanceof \stdClass ||
            get_class($value) === 'stdClass' ||
            is_subclass_of($value, \stdClass::class)
        );
    }

    /**
     * Convert array keys with optimizations
     */
    private function convertArrayKeys(array $data, int $depth = 1): array
    {
        if ($depth > $this->maxDepth) {
            return $data;
        }

        $converted = [];

        foreach ($data as $key => $value) {
            if (!is_string($key)) {
                $converted[$key] = $this->processValue($value, $depth + 1);
                continue;
            }

            $newKey = $this->transformKey($key);
            $converted[$newKey] = $this->processValue($value, $depth + 1);
        }

        return $converted;
    }

    /**
     * Convert object properties with optimizations
     */
    private function convertObjectProperties(object $data, int $depth = 1): object
    {
        if ($depth > $this->maxDepth) {
            return $data;
        }

        $converted = [];

        foreach (get_object_vars($data) as $key => $value) {
            $newKey = $this->transformKey($key);
            $converted[$newKey] = $this->processValue($value, $depth + 1);
        }

        return (object) $converted;
    }

    /**
     * Process individual values
     */
    private function processValue($value, int $depth)
    {
        if ($this->shouldSkipValue($value)) {
            return $value;
        }

        if (is_array($value)) {
            return $this->convertArrayKeys($value, $depth);
        }

        if (is_object($value)) {
            return $this->convertObjectProperties($value, $depth);
        }

        return $value;
    }

    /**
     * Entry point to convert case of keys
     */
    public function convertKeys($data)
    {
        if ($data === null || is_scalar($data)) {
            return $data;
        }

        return $this->processValue($data, 1);
    }
}
