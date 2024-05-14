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

        $content = $response->getContent();

        $case = config('key-case.response_case', 'camel');

        $converted = (new KeyTransformer($case))->convertKeys(json_decode($content));

        $response->setContent(json_encode($converted));

        return $response;
    }
}
