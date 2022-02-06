<?php

namespace App\Modules\EuCookiesConsent\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Response;

// Ver
// https://42coders.com/add-a-cookie-consent-to-your-laravel-application-in-just-5-minutes
// https://github.com/42coders/eu-cookie-consent


class EuCookiesConsentController extends Controller
{
    public function saveCookie(Request $request)
    {
        $reject_all = $request->input('reject_all', '0');

        $cookies_groups = [];
        if ($reject_all != '1') {
            $cookies_groups = $request->except(['_token', 'reject_all']);
        }
        $cookies_groups = array_merge($cookies_groups, ['cookies-necessary' => '1']);

        $cookie = Cookie::make(
            config('eu-cookie-consent.cookie_name'),
            json_encode($cookies_groups),
            config('eu-cookie-consent.cookie_lifetime')
        );

        return redirect()->back()->withCookie($cookie);
    }
}
