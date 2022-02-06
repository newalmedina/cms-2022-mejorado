<?php

namespace App\Http\Controllers\DashboardController;

use App\Models\UserConfig;
use App\Http\Controllers\AdminController;

class DashboardController extends AdminController
{
    protected $page_title_icon = '<i class="fas fa-tachometer-alt" aria-hidden="true"></i>';

    public function index()
    {
        // Verificamos cual es la main dashboard seleccionada
        $config = UserConfig::where("user_id", auth()->user()->id)->first();
        if (!empty($config->dashboard)) {
            return redirect($config->dashboard);
        }

        // Mostramos la dashboard por defecto
        $page_title = trans("dashboard/admin_lang.Dashboard");
        $page_description = trans("dashboard/admin_lang.estadisticas_info");

        return view(
            'modules.home.admin_index',
            compact(
                'page_title',
                'page_description'
            )
        )
        ->with('page_title_icon', $this->page_title_icon);
    }

    public function saveState()
    {
        // Verificamos que sea usuario
        if (!auth()->guest()) {
            $config = UserConfig::where("user_id", auth()->user()->id)->first();
            if (empty($config)) {
                $config = new UserConfig();
                $config->user_id = auth()->user()->id;
                $config->sidebar = false;
            }
            $config->sidebar = !$config->sidebar;
            $config->save();
        }
    }

    public function changeSkin(\Illuminate\Http\Request $request)
    {
        $skin = $request->get("skin", 'skin-blue');
        // Verificamos que sea usuario
        if (!auth()->guest()) {
            $config = UserConfig::where("user_id", auth()->user()->id)->first();
            if (empty($config)) {
                $config = new UserConfig();
                $config->user_id = auth()->user()->id;
            }
            $config->skin = $skin;
            $config->save();
        }
    }
}
