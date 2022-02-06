<?php

namespace Clavel\CrudGenerator\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleField extends Model
{
    protected $table = "crud_module_fields";


    public function type()
    {
        return $this->hasOne('Clavel\CrudGenerator\Models\FieldType', 'slug', 'field_type_slug');
    }

    public function scopeInList($query)
    {
        return $query->where("in_list", true);
    }

    public function scopeNotInList($query)
    {
        return $query->where("in_list", false);
    }

    public function scopeInCreate($query)
    {
        return $query->where("in_create", true);
    }

    public function scopeNotInCreate($query)
    {
        return $query->where("in_create", false);
    }

    public function scopeNotInGrid($query)
    {
        return $query->where("order_create", 0);
    }
}
