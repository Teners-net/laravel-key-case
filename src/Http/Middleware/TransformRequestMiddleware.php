<?php

namespace Teners\LaravelKeyCase\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

        try {
            $converted = $transformer->convertKeys($content);
            $request->replace($converted);
        } catch (\Exception $e) {
            config('app.debug') && throw new $e;

            Log::warning('Key transformation failed for request', [
                'route' => $request->path(),
                'error' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ]);
        }

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
