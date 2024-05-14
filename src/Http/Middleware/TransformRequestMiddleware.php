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

        $case = config('key-case.request_case', 'snake');

        $converted = (new KeyTransformer($case))->convertKeys($content);

        $request->replace($converted);


        return $next($request);
    }
}
