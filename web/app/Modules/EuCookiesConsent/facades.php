<?php

use App\Modules\EuCookiesConsent\Facades\EuCookieConsent;

$this->app->singleton('eu-cookie-consent', function () {
    return new EuCookieConsent();
});
