<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;

class UserAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if($request->path() == 'dashboard' && $user->roles == 'admin') {
            return redirect('/dashboard-admin');
        } else if ($request->path() == 'dashboard-admin' && $user->roles == 'user') {
            return redirect('/dashboard');
        }
        return $next($request);
    }
}
