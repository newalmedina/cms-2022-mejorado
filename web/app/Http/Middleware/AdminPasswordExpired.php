<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminPasswordExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (config('auth.password_expiration') && !auth()->guest()) {
            $user = $request->user();
            $password_changed_at = new Carbon(($user->password_changed_at) ? $user->password_changed_at : $user->created_at);

            if (Carbon::now()->diffInDays($password_changed_at) >= config('auth.password_expires_days')) {
                return redirect()->route('admin.password.expired');
            }
        }

        return $next($request);
    }
}
