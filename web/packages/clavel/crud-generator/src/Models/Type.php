<?php

namespace Clavel\CrudGenerator\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = "crud_types";

    public function scopeActives($query)
    {
        return $query->where("active", true);
    }
}
