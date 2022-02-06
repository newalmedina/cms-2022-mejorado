<?php

namespace App\Http\Middleware;

use App\Models\UserConfig;
use Closure;
use Illuminate\Support\Facades\Session;

class FrontDashboard
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
        // Si somos usuario autenticados
        if (!auth()->guest()) {
            // Leemos los valores de configuracion del skin y del menu lateral
            $config = UserConfig::where("user_id", auth()->user()->id)->first();
            if (empty($config)) {
                // No existe. Lo creamos por defecto
                $config = new UserConfig();
                $config->user_id = auth()->user()->id;
                $config->skin = '';
                $config->sidebar = true;
                $config->save();
            }
            // Establecemos los valores leidos en Sesion del sidebar y del skin
            if ($config->sidebar) {
                Session::remove('sidebarState');
            } else {
                //colapse sidebar
                Session::put('sidebarState', 'sidebar-left-collapsed');
            }
        }


        return $next($request);
    }
}
