<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SkinService;

class SkinServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('skin', function ($app) {
            return new SkinService();
        });
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
