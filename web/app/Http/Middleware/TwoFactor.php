<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TwoFactor
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
        $user = auth()->user();

        if (auth()->check() && $user->hasTwoFactor() && $user->userTwoFactor->two_factor_code) {
            if ($user->userTwoFactor->two_factor_expires_at->lt(now())) {
                $user->userTwoFactor->resetTwoFactorCode();
                $user->userTwoFactor->save();

                auth()->logout();

                if ($request->is('admin/*') || $request->route()->getPrefix() == "/admin" ||
                substr($request->route()->getPrefix(), 0, 6) === "admin/") {
                    return redirect()->route('admin.login')
                        ->withError('The two factor code has expired. Please login again.');
                } else {
                    return redirect()->route('login')
                        ->withError('The two factor code has expired. Please login again.');
                }
            }

            // Y estamos intentando acceder a administracion
            if ($request->is('admin/*') || $request->route()->getPrefix() == "/admin" ||
                substr($request->route()->getPrefix(), 0, 6) === "admin/") {
                if (!$request->is('admin/2fa/verify*')) {
                    return redirect()->route('admin.2fa.verify.index');
                }
            } else {
                if (!$request->is('2fa/verify*')) {
                    return redirect()->route('2fa.verify.index');
                }
            }
        }

        return $next($request);
    }
}
