<?php

namespace App\Http\Middleware;

use Closure;

class OnlyHttps
{
    public function handle($request, Closure $next)
    {
        return !$request->secure() && config('app.env') === 'production' ?
            redirect()->secure($request->getRequestUri()) :
            $next($request);
    }
}
