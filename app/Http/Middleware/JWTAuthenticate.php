<?php
namespace App\Http\Middleware;


use Closure;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTAuthenticate extends BaseMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');
        $request->offsetSet('token', $token);
        try {
            $this->authenticate($request);
        } catch (UnauthorizedHttpException $e) {
            return response()->json(array(
                'code' => 40001,
                'data' => $e->getMessage(),
                'msg' => '未登录或者token已过期',
                'success' => false
            ));
        }

        return $next($request);
    }
}
