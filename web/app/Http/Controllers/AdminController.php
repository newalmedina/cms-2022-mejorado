<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    protected $access_permission = null;

    public function __construct()
    {
        // Para acceder a cualquier controllador de administración se de cumplir que:
        // admin => Estamos logados y tenemos acceso a la administacion
        // twofactor => si el usuario tiene activado two factor
        // verified => Si implementa la interfaz MustVerifyEmail el User tiene que estar verificado. Pasamos la vista
        $this->middleware(['admin', 'twofactor', 'verified:admin.verification.notice']);

        // Tenemos permisos de acceso
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
