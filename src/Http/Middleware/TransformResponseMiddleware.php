<?php

namespace Teners\LaravelKeyCase\Http\Middleware;

use Closure;
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

        $currentRoute = $request->path();

        if (in_array($currentRoute, $this->getRoutesToIgnore())) {
            return $response;
        }

        $content = $response->getContent();

        $case = config('key-case.response_case', 'camel');

        $converted = (new KeyTransformer($case))->convertKeys(json_decode($content));

        $response->setContent(json_encode($converted));

        return $response;
    }

    /**
     * Get the list of routes to ignore for request transformation.
     *
     * @return array
     */
    private function getRoutesToIgnore(): array
    {
        $ignoreRoutes = config('key-case.ignore', []);
        $ignoreResponseRoutes = config('key-case.ignoreResponse', []);

        return array_merge(
            $ignoreRoutes,
            $ignoreResponseRoutes,
        );
    }
}
