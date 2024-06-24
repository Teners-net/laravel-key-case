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
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $content = $request->all();

        $currentRoute = $request->path();

        if (in_array($currentRoute, $this->getRoutesToIgnore())) {
            return $next($request);
        }

        $case = config('key-case.request_case', 'snake');

        $converted = (new KeyTransformer($case))->convertKeys($content);

        $request->replace($converted);


        return $next($request);
    }

    /**
     * Get the list of routes to ignore for request transformation.
     *
     * @return array
     */
    private function getRoutesToIgnore(): array
    {
        $ignoreRoutes = config('key-case.ignore', []);
        $ignoreRequestRoutes = config('key-case.ignoreRequest', []);

        return array_merge(
            $ignoreRoutes,
            $ignoreRequestRoutes,
        );
    }
}
