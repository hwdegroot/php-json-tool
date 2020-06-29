<?php

namespace App\Http\Middleware\Api;

use Closure;
use Str;

class RequestHeader
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param null|string              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->wantsJson() && Str::startsWith($request->path(), 'api/')) {
            // Add Json header to request
            $request->server->set('HTTP_Accepts', 'application/json');
            $request->server->set('HTTP_Content-Type', 'application/json');

            return $next($request);
        }

        return $next($request);
    }
}
