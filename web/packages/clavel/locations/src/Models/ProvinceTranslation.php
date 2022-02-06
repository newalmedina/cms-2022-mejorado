<?php

namespace Clavel\Locations\Models;

use Illuminate\Database\Eloquent\Model;

class ProvinceTranslation extends Model
{
    protected $table = "province_translations";

    public $timestamps = false;

    public function province()
    {
        return $this->belongsTo('Clavel\Locations\Models\Province');
    }
}
