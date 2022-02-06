<?php

namespace Clavel\Locations\Models;

use Illuminate\Database\Eloquent\Model;

class CcaaTranslation extends Model
{
    protected $table = "ccaa_translations";

    public $timestamps = false;

    protected $fillable = ['name'];

    public function ccaa()
    {
        return $this->belongsTo('Clavel\Locations\Models\Ccaa');
    }
}
