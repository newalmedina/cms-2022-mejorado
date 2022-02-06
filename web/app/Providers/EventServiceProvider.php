<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        // Todos los intentos de login fallidos son interceptados por este evento y son registrados en la base de datos
        'Illuminate\Auth\Events\Failed' => [
            'App\Listeners\LogFailedAuthenticationAttempt',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // Observer del modelo User principalmente para la grabación de contraseñas historicas
        User::observe(UserObserver::class);
    }
}
