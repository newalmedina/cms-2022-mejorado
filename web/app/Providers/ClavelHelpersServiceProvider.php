<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class ClavelHelpersServiceProvider extends ServiceProvider
{
    protected $files;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->files = new Filesystem();

        // Registramos el Helper de Excel
        $helper = app_path().'/Helpers/Clavel/ExcelHelper.php';

        if ($this->files->exists($helper)) {
            require_once $helper;
        }

        // Registramos el Helper de Settings
        $helper = app_path().'/Helpers/Clavel/SettingsHelper.php';

        if ($this->files->exists($helper)) {
            App::bind('Settings', function () {
                return new \App\Helpers\Clavel\SettingsHelper();
            });
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
