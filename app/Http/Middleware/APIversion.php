<?php

namespace App\Http\Middleware;

use Closure;

class APIversion
{
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  Closure $next
     * @param  String $version
     *
     * @return mixed
     */
    public function handle($request, Closure $next, String $version)
    {
        config(['app.api_version' => $version]);

        return $next($request);
    }
}
