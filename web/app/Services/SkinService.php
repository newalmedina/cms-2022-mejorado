<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

/**
 * Servicio que devuelve las classes registradas del Skin AdminLTE 3
 */
class SkinService
{
    protected $keys = [];


    /**
     * Busca la clave en el listado
     */
    public function get($key)
    {
        // Miramos si ya ha sido procesado
        if (isset($this->keys[$key])) {
            return $this->keys[$key];
        }

        // Parece que no ha sido procesado anteriormente
        // Miramos si esta en la sessión
        $skin = Session::get('skin');
        $skinData = json_decode($skin);

        // Ahora guardamos los elementos del skin
        // { "skin" : "skin-blue", "data": ["dark","","bg-lightblue","navbar-lightblue"]}
        if (!empty($skinData)) {
            $this->keys["header"] = 'navbar-'.$skinData->data[0].' '.$skinData->data[1];
            $this->keys["brand"] = $skinData->data[1];
            $this->keys["sidebar"] = $skinData->data[4];
        }

        // Buscamos si esta en la sesión
        if (isset($this->keys[$key])) {
            return $this->keys[$key];
        }

        // Ponemos valores por defecto
        $this->keys["header"] = 'navbar-dark navbar-lightblue';
        $this->keys["brand"] = 'navbar-lightblue';
        $this->keys["sidebar"] = 'sidebar-dark-lightblue';

        // Buscamos si esta en los valores por defecto
        if (isset($this->keys[$key])) {
            return $this->keys[$key];
        }

        return "";
    }
}
