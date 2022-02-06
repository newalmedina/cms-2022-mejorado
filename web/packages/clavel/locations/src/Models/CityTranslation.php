<?php

namespace Clavel\Locations\Models;

use Illuminate\Database\Eloquent\Model;

class CityTranslation extends Model
{
    protected $table = "city_translations";

    public $timestamps = false;

    public function city()
    {
        return $this->belongsTo('Clavel\Locations\Models\City');
    }
}
