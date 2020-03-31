<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\UnauthorizedException;

class UserTypeAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param int $type
     * @return mixed
     */
    public function handle($request, Closure $next, $type = 1)
    {
        if(auth()->guard('api')->user()->type != $type) {
            if (! $request->expectsJson()) {
                return route('login');
            } else {
                return response()->json([
                    'message' => 'UnAuthorized.'
                ], 401);
            }
        }

        return $next($request);
    }
}
