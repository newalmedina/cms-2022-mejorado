<?php

namespace App\Modules\EuCookiesConsent\Facades;

use Illuminate\Support\Facades\Cookie;

class EuCookieConsent
{
    /**
     * hasSettingsCookie returns if the user have set a Cookie to determine if he already accepted your Cookie Consent
     *
     * @return bool
     */
    public static function hasSettingsCookie(): bool
    {
        return empty(self::getSettingsCookie()) ? false : true;
    }

    /**
     * getSettingsCookie reads the Cookie and decode and return it.
     *
     * @return mixed
     */
    private static function getSettingsCookie()
    {
        return json_decode(Cookie::get(config('eu-cookie-consent.cookie_name'), ''));
        //return json_decode($_COOKIE[config('eu-cookie-consent.cookie_name')] ?? '');
    }

    /**
     * getUserCookieSetting checks if a specific Cookie was accepted by the User.
     *
     * @param string $cookieName
     * @return bool
     */
    private static function getUserCookieSetting(string $cookieName): bool
    {
        $settings = self::getSettingsCookie();

        if (empty($settings)) {
            return false;
        }

        return isset($settings->{$cookieName}) && $settings->{$cookieName} == '1' ? true : false;
    }

    /**
     * canIUse returns you if the User give you a specific permission
     *
     * @param $cookie (key of the cookies in the config)
     * @return bool
     */
    public static function canIUse($cookie): bool
    {
        return self::getUserCookieSetting($cookie) ?? false;
    }

    /**
     * returns a specific Cookie from the Config
     *
     * @param string $cookieName
     * @return mixed|null
     */
    private static function getCookie(string $cookieName)
    {
        $config = config('eu-cookie-consent.cookies');

        foreach ($config['categories'] as $category) {
            if (isset($category['cookies'][$cookieName])) {
                return $category['cookies'][$cookieName];
            }
        }

        return null;
    }

    /**
     * getPopup returns the Html of the Popup.
     *
     * @return View|string
     */
    public static function getCookieHtml()
    {
        if (empty(config('eu-cookie-consent.enabled')) || config('eu-cookie-consent.enabled') == false  || !empty($_COOKIE[config('eu-cookie-consent.cookie_name')])) {
            return '';
        }

        // $config = config('eu-cookie-consent.cookies');
        // $multiLanguageSupport = config('eu-cookie-consent.multilanguage_support');
        // return view('eu-cookie-consent::popup', [
        //     'config' => $config,
        //     'multiLanguageSupport' => $multiLanguageSupport,
        // ]);

        return view('EuCookiesConsent::cookies_footer');
    }
}
