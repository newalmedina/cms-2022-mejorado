<?php

namespace App\Modules\EuCookiesConsent\Facades;

use Illuminate\Support\Facades\Facade;

class EuCookieConsentFacade extends Facade
{
    /**
         * Get the registered name of the component.
         *
         * @return string
         */
    protected static function getFacadeAccessor()
    {
        return 'eu-cookie-consent';
    }
}
