<?php

namespace App\Http\Controllers\Home;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FrontHomeController extends Controller
{
    public function index()
    {
        // Si solo hay backoffice vamos a administracion
        if (config("general.only_backoffice", false)) {
            return redirect()->to('admin/');
        } else {
            // Si tenemos home permitimos mostrarla
            if (config("general.has_home", true)) {
                $page_title = trans('home/front_lang.home');

                return view(
                    'modules.home.front_index',
                    compact(
                        'page_title'
                    )
                );
            } else {
                // Vamos a login si no estamos logados
                if (auth::guest()) {
                    return redirect()->to('login');
                }
                // Estamos logados => vamos a dashboard
                return redirect()->to('dashboard');
            }
        }
    }
}
