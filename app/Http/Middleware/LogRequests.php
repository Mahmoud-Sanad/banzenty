<?php

namespace App\Http\Middleware;

use Closure;

class logRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        info($request->path());
        info($request->header('Authorization'));
        info($request->all());

        return $next($request);
    }
}
