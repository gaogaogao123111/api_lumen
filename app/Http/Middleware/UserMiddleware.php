<?php

namespace App\Http\Middleware;
use Closure;
class UserMiddleware
{

    public function handle($request, Closure $next)
    {
        if ($request->isMethod('OPTIONS')) {
            $response=response('');
        }else{
            $response = $next($request);
        }

        return $response;
    }
}
