<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Store;

class SessionExpiredMiddleware
{
    protected $session;

    public function __construct(Store $session){
        $this->session = $session;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificamos si no estamos en logout
        $isLoggedIn = $request->path() != 'logout' && $request->path() != 'admin/logout';

        // Guardamos en sesión la ultima vez que actualizo la sessión
        if(! session('lastActivityTime')) {
            $this->session->put('lastActivityTime', time());
        }
        elseif(time() - $this->session->get('lastActivityTime') > config('auth.session_timeout')){
            // Forzamos logout
            $this->session->forget('lastActivityTime');
            //$cookie = cookie('intend', $isLoggedIn ? url()->current() : '/');
            auth()->logout();
        }

        $isLoggedIn ? $this->session->put('lastActivityTime', time()) : $this->session->forget('lastActivityTime');
        return $next($request);

    }
}
