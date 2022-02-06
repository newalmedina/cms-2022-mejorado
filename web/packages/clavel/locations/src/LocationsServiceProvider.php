<?php

namespace Clavel\Locations;

use Illuminate\Support\Facades\Config;
use App\Providers\BaseServiceProvider;
use Illuminate\Filesystem\Filesystem;

class LocationsServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->module = "locations";

        $this->init(__DIR__, __NAMESPACE__);

        $this->registerViews(__DIR__);

        $this->publish(__DIR__);
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews($resources_dir)
    {
        $viewPath = base_path('resources/views/clavel/'.$this->module);

        $sourcePath = $resources_dir.'/Views';

        $this->registerMenuViews($resources_dir);

        if ($this->files->isDirectory($sourcePath)) {
            $this->loadViewsFrom(array_merge(array_map(function ($path) {
                return $path . '/clavel/'.$this->module;
            }, Config::get('view.paths')), [$sourcePath]), $this->module);
        }
    }



    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->files = new Filesystem();


        // Register the controller
        $this->app->make('Clavel\Locations\Controllers\AdminCountriesController');
        $this->app->make('Clavel\Locations\Controllers\AdminCcaasController');
        $this->app->make('Clavel\Locations\Controllers\AdminProvincesController');
        $this->app->make('Clavel\Locations\Controllers\AdminCitiesController');

        // Registramos la APi
        $this->app->make('Clavel\Locations\Api\v1\CountriesApiController');
        $this->app->make('Clavel\Locations\Api\v1\CcaasApiController');
        $this->app->make('Clavel\Locations\Api\v1\ProvincesApiController');
        $this->app->make('Clavel\Locations\Api\v1\CitiesApiController');


    }
}
