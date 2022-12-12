<?php

namespace App\Http\Middleware;

use Closure;
use Helper\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Kreait\Firebase\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
//     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = \auth()->user();
        if ($user && $user->role != 'admin'){
            return ResponseService::responseJsonError(Response::HTTP_FORBIDDEN,trans('errors.permission'));
        }
        return $next($request);
    }
}
