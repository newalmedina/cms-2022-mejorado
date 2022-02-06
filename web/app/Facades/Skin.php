<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Skin extends Facade
{
    /**
     * Devuelve el string para acceder al Service Container para acceder al Service provider
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return "skin";
    }
}
