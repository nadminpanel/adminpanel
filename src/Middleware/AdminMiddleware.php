<?php

namespace NAdminPanel\AdminPanel\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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
        if(Auth::check() && Auth::user()->hasAnyRole(['admin', 'developer'])) {
            return $next($request);
        } elseif(Auth::check()) {
            return redirect()->to(config('nadminpanel.user_landing_link'));
        } else {
            return redirect()->to(config('nadminpanel.guest_landing_link'));
        }
    }
}
