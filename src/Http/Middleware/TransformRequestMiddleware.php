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

        $converted = (new KeyTransformer())->convertKeys($content);

        $request->replace($converted);

        return $next($request);
    }
}
