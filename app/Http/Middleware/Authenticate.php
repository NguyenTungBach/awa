<?php

namespace App\Http\Middleware;

use Closure;
use Helper\ResponseService;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\JWTAuth;

class Authenticate extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        try {
            if (!$token = $this->auth->setRequest($request)->getToken()) {
                return ResponseService::responseJsonError(CODE_UNAUTHORIZED, '', 'token not provided');
            }
            if (!$user = $this->auth->parseToken()->authenticate()) {
                return ResponseService::responseJsonError(CODE_NOT_FOUND, '', 'user not found');
            }
        } catch (TokenExpiredException $e) {
            return ResponseService::responseJsonError(CODE_UNAUTHORIZED, '', 'token expire');
        } catch (JWTException $e) {
            return ResponseService::responseJsonError(CODE_UNAUTHORIZED, $e->getMessage(), 'Exception');
        }
        return $next($request);
    }
}
