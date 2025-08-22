<?php

namespace Teners\LaravelKeyCase\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Teners\LaravelKeyCase\Services\KeyTransformer;

class TransformRequestMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldIgnoreRoute($request->path())) {
            return $next($request);
        }

        $content = $request->all();
        if (empty($content)) {
            return $next($request);
        }

        $transformer = $this->getTransformer();

        $converted = $transformer->convertKeys($content);
        $request->replace($converted);

        return $next($request);
    }

    /**
     * Get transformer instance
     */
    private function getTransformer(): KeyTransformer
    {
        $case = config('key-case.request_case', 'snake');;

        return new KeyTransformer($case);
    }

    private function shouldIgnoreRoute(string $route): bool
    {
        return in_array($route, array_merge(
            config('key-case.ignore', []),
            config('key-case.ignore_request', [])
        ));
    }
}
