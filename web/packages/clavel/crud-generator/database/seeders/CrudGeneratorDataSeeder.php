<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Clavel\CrudGenerator\Models\Type;
use Clavel\CrudGenerator\Models\Theme;
use Clavel\CrudGenerator\Models\FieldType;

class CrudGeneratorDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('crud_types')->delete();

        $types = array(
            [1, 'Administración', 'admin', true],
            [2, 'Front end', 'front', true]
        );

        foreach ($types as $t) {
            $type = new Type();
            $type->id = $t[0];
            $type->name = $t[1];
            $type->slug = $t[2];
            $type->active = $t[3];
            $type->save();
        }

        DB::table('crud_themes')->delete();

        $themes = array(
            [1, 'Admin LTE 2', 'adminlte2', true],
            [2, 'Admin Porto 3', 'portoadmin3', false],
            [3, 'Admin LTE 3', 'adminlte3', true],
            [4, 'Admin Porto 4', 'portoadmin4', true]
        );

        foreach ($themes as $t) {
            $theme = new Theme();
            $theme->id = $t[0];
            $theme->name = $t[1];
            $theme->slug = $t[2];
            $theme->active = $t[3];
            $theme->save();
        }


        DB::table('crud_field_types')->delete();

        $fieldTypes = array(
            [1, 'Auto incremento', 'auto_increment', false],
            [2, 'Text', 'text', true],
            [3, 'Email', 'email', true],
            [4, 'Textarea', 'textarea', true],
            [5, 'Password', 'password', true],
            [6, 'Radio', 'radio', true],
            [19, 'Radio si/no', 'radio_yes_no', true],
            [7, 'Select', 'select', true],
            [8, 'Checkbox', 'checkbox', true],
            [9, 'Integer', 'number', true],
            [10, 'Float', 'float', true],
            [11, 'Money', 'money', true],
            [12, 'Date Picker', 'date', true],
            [13, 'Date / Time Picker', 'datetime', true],
            [14, 'Time Picker', 'time', true],
            [15, 'File', 'file', true],
            [17, 'BelongsTo Relationship', 'belongsToRelationship', true],
            [18, 'BelongsToMany Relationship', 'belongsToManyRelationship', true],
            [20, 'Color', 'color', true],
            [21, 'Checkbox Multiple', 'checkboxMulti', true],
            [22, 'Image', 'image', true]
        );

        foreach ($fieldTypes as $fieldType) {
            $type = new FieldType();
            $type->id = $fieldType[0];
            $type->name = $fieldType[1];
            $type->slug = $fieldType[2];
            $type->active = $fieldType[3];
            $type->save();
        }
    }
}
