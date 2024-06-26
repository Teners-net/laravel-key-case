<?php

namespace Teners\LaravelKeyCase\Services;

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

    private $case;

    public function __construct(string $case)
    {
        if (!in_array($case, self::CASES)) {
            throw new InvalidArgumentException('Invalid case ' . (string) $case);
        }

        $this->case = $case;
    }

    /**
     * Convert keys of an associative array
     *
     * @param array $data
     */
    private function convertArrayKeys(array $data): array
    {
        $converted = [];

        foreach ($data as $key => $value) {
            $converted[Str::{$this->case}($key)] = is_array($value) || is_object($value)
                ? $this->convertKeys($value)
                : $value;
        }

        return $converted;
    }

    /**
     * Convert properties of an object
     *
     * @param object $data
     */
    private function convertObjectProperties(object $data): object
    {
        $converted = [];
        foreach (get_object_vars($data) as $key => $value) {
            $converted[Str::{$this->case}($key)] = is_array($value) || is_object($value)
                ? $this->convertKeys($value)
                : $value;
        }
        return (object) $converted;
    }

    /**
     * Entry point to convert case of keys
     *
     * @param mixed $data The data to be converted (could be an array or an object).
     */
    public function convertKeys(mixed $data): mixed
    {
        if (is_array($data)) {
            return $this->convertArrayKeys($data);
        }

        if ($data instanceof \Illuminate\Http\UploadedFile) {
            return $data;
        }

        if (is_object($data)) {
            return $this->convertObjectProperties($data);
        }

        return $data;
    }
}
