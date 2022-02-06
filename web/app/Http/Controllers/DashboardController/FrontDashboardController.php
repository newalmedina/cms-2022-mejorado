<?php

namespace App\Http\Controllers\DashboardController;

use App\Models\UserConfig;
use App\Http\Controllers\FrontController;

class FrontDashboardController extends FrontController
{
    protected $page_title_icon = '<i class="fa fa-line-chart" aria-hidden="true"></i>';

    public function index()
    {
        // Verificamos cual es la main dashboard seleccionada
        $config = UserConfig::where("user_id", auth()->user()->id)->first();
        if (!empty($config->dashboard)) {
            return redirect($config->dashboard);
        }

        // Mostramos la dashboard por defecto
        $page_title = trans("dashboard/front_lang.dashboard");
        $page_description = trans("dashboard/front_lang.estadisticas_info");

        return view(
            'modules.dashboard.front_index',
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
}
