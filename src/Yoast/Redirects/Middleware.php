<?php

namespace WP4Laravel\Yoast\Redirects;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware that intercepts every request and checks if a redirect in Yoast
 * Premium is applicable. If so, stops further processing and returns the
 * redirect.
 */
class Middleware
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function handle($request, Closure $next)
    {
        $redirect = $this->service->handle($request);
        return $redirect ? $redirect : $next($request);
    }
}
