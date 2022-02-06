<?php

namespace Clavel\CrudGenerator\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleGrid extends Model
{
    protected $table = "crud_module_grid";

    public function module()
    {
        return $this->belongsTo('Clavel\CrudGenerator\Models\Module', 'id', 'crud_module_id');
    }

    public function field()
    {
        return $this->hasOne('Clavel\CrudGenerator\Models\ModuleField', 'id', 'crud_module_field_id');
    }
}
