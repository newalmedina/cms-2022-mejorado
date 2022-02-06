<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\AdminDashboard::class, // Establece el skin y el menu lateral de la administración
            \App\Http\Middleware\FrontDashboard::class, // Establece el skin y el menu lateral del front
            \App\Http\Middleware\LastOnlineAt::class, // Guarda la ultima vez que el usuario se conecto
            \App\Http\Middleware\ApplicationLanguage::class, // Establece el idioma de la aplicación
            \App\Http\Middleware\SessionExpiredMiddleware::class, // Verificamos si la sesión ha caducado y lo llevamos a home haciendo logout
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\LastOnlineAt::class // Guarda la ultima vez que el usuario se conecto
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'admin' =>  \App\Http\Middleware\AdminMiddleware::class, // Verifica si tiene acceso a admnistracion
        'twofactor'     => \App\Http\Middleware\TwoFactor::class, // Verifica 2FA
        'front_password_expired' => \App\Http\Middleware\FrontPasswordExpired::class, // Expiracion de contraseña en front
        'admin_password_expired' => \App\Http\Middleware\AdminPasswordExpired::class, // Expiracion de contraseña en admin
        'has_center' => \App\Http\Middleware\HasCenterMiddleware::class, // Verificación de centro asignado
    ];
}
