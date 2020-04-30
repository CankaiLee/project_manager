<?php
namespace App\Http\Middleware;


use Closure;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTAuthenticate extends BaseMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');
        $request->offsetSet('token', $token);
        $this->authenticate($request);

        return $next($request);
    }
}
