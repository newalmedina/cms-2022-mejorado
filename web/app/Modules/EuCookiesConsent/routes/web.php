<?php


Route::group(
    [
        'namespace' => 'App\Modules\EuCookiesConsent\Controllers',
        'middleware' => ['web']
    ],
    function () {
        Route::post(config('eu-cookie-consent.route'), 'EuCookiesConsentController@saveCookie');
    }
);
