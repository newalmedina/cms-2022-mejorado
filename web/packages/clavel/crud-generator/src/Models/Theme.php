<?php

namespace Clavel\CrudGenerator\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $table = "crud_themes";

    public function scopeActives($query)
    {
        return $query->where("active", true);
    }
}
