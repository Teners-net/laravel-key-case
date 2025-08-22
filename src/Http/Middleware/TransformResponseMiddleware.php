<?php

namespace Teners\LaravelKeyCase\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Teners\LaravelKeyCase\Services\KeyTransformer;

class TransformResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldIgnoreRoute($request->path())) {
            return $response;
        }

        if (!$this->isJsonResponse($response)) {
            return $response;
        }

        $content = $response->getContent();

        $transformer = $this->getTransformer();
        $converted = $transformer->convertKeys(json_decode($content));

        $response->setContent(json_encode($converted));

        return $response;
    }

    /**
     * Check if response is JSON
     */
    private function isJsonResponse(Response $response): bool
    {
        if ($response instanceof JsonResponse) {
            return true;
        }

        $contentType = $response->headers->get('content-type', '');
        return str_contains($contentType, 'application/json') ||
               str_contains($contentType, 'text/json');
    }

    /**
     * Get transformer instance
     */
    private function getTransformer(): KeyTransformer
    {
        $case = config('key-case.response_case', 'camel');

        return new KeyTransformer($case);
    }

    private function shouldIgnoreRoute(string $route): bool
    {
        return in_array($route, array_merge(
            config('key-case.ignore', []),
            config('key-case.ignore_response', [])
        ));
    }
}
