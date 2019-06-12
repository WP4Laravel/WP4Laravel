<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class ProfileJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (
            config('app.debug_api') &&
            $response instanceof JsonResponse &&
            app()->bound('debugbar') &&
            app('debugbar')->isEnabled() && (is_object($response->getData()) || is_array($response->getData()))
        ) {
            $queries = app('debugbar')->getData()['queries'];
            $response->setData([
                '_debugbar' => [
                    'nb_statements' => $queries['nb_statements'],
                    'statements' => collect($queries['statements'])->map(function ($query) {
                        return $query['sql'];
                    })
                ],
            ] + $response->getData(true));
        }

        return $response;
    }
}
