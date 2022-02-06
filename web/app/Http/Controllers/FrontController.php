<?php

namespace App\Http\Controllers;

class FrontController extends Controller
{
    public function __construct()
    {
        // Para acceder a cualquier controllador de administración se de cumplir que:
        // auth => Estamos logados y tenemos acceso a las partes privadas
        // twofactor => si el usuario tiene activado two factor
        // verified => Si implementa la interfaz MustVerifyEmail el User tiene que estar verificado. Pasamos la vista
        $this->middleware(['auth', 'twofactor', 'verified:verification.notice']);


        // Since verson 5.3 we can't acess Auth directly see next link
        // https://github.com/laravel/docs/blob/5.3/upgrade.md#session-in-the-constructor
        $this->middleware(function ($request, $next) {
            if (isset($this->access_permission)) {
                if (!auth()->user()->isAbleTo($this->access_permission)) {
                    app()->abort(403);
                }
            }
            return $next($request);
        });
    }
}
