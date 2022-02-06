<?php

namespace Clavel\Locations\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class CountryTranslation extends Model
{
    protected $table = "country_translations";

    public $timestamps = false;

    protected $fillable = ['name', 'short_name'];

    public function country()
    {
        return $this->belongsTo('Clavel\Locations\Models\Country');
    }
}
