<?php

namespace App\Helpers;

class Utils
{
    public static function calculateTaxes($price, $percent)
    {
        if ($percent > 0) {
            return (($price * $percent) / 100);
        }
        return "123";
    }
    public static function priceWhitTaxes($price, $taxes = 0)
    {
        return $price + $taxes;
    }
}
