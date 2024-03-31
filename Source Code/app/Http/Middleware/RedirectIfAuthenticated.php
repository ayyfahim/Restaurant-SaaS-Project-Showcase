<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if (Auth::guard($guard)->check()) {
            if ('store' === $guard) {
                return redirect(RouteServiceProvider::STORE);
            }
            if ('waiter' === $guard) {
                return redirect(RouteServiceProvider::WAITER);
            }
            if ('kitchen' === $guard) {
                return redirect(RouteServiceProvider::KITCHEN);
            }
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
