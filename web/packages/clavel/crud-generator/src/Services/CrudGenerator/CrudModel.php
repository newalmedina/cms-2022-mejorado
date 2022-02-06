<?php

namespace Clavel\CrudGenerator\Services\CrudGenerator;

use Clavel\CrudGenerator\Models\Module;
use Clavel\CrudGenerator\Models\ModuleField;
use Clavel\CrudGenerator\Services\CrudGenerator;
use Clavel\CrudGenerator\Services\ModelSelector;
use Illuminate\Support\Str;

class CrudModel
{
    private $crudGenerator = null;

    public function __construct(CrudGenerator $crudGenerator)
    {
        $this->crudGenerator = $crudGenerator;
    }

    public function generate()
    {
        $this->model($this->crudGenerator->module);
    }

    protected function model(Module $module)
    {
        $name = $module->model;

        $includes = "";
        $traits = [];

        // Vemos si hay campos multiidioma
        $hasLang = ModuleField::where('crud_module_id', $module->id)
            ->where('can_modify', true)
            ->where('is_multilang', true)
            ->count();

        $langData = "";
        if ($hasLang) {
            $fieldPath = $this->crudGenerator->resourcePath . "Models/translatable.stub";
            $fieldTemplate = $this->crudGenerator->getStub($fieldPath);

            $fields = ModuleField::where('crud_module_id', $module->id)
                ->where('field_type_slug', '<>', 'auto_increment')
                ->where('is_multilang', true)
                ->get();
            $fillablesMultilang = "";
            if ($fields) {
                foreach ($fields as $field) {
                    $fillablesMultilang .= "'" . $field->column_name . "',\n";
                }
            }

            $fieldTemplate = str_replace('{{__translatable_fields__}}', $fillablesMultilang, $fieldTemplate);
            $langData = $fieldTemplate;
        }

        // Vemos si hay campos select o radio o multicheck
        $fields = ModuleField::where('crud_module_id', $module->id)
            ->where(function ($q) {
                $q->where('field_type_slug', '=', 'select')
                    ->orWhere('field_type_slug', '=', 'radio')
                    ->orWhere('field_type_slug', '=', 'checkboxMulti');
            })
            ->get();

        $constSelects = "";
        foreach ($fields as $field) {
            $data = $field->data;
            if (!empty($data)) {
                $someArray = json_decode($data, true);
                $constSelects .= "const " . strtoupper($field->column_name) .
                    "_" . strtoupper($field->field_type_slug) . " = [\n";
                $arrayValores = [];
                foreach ($someArray as $key => $value) {
                    $arrayValores[] = "'" . $value[0] . "'";
                }

                $constSelects .= implode(",\n", $arrayValores);
                $constSelects .= "\n];\n";
            }
        }

        // Vemos si hay softdelete
        $softDeletes = "";
        if ($module->has_soft_deletes) {
            $includes .= "use Illuminate\Database\Eloquent\SoftDeletes;\r\n";
            // $softDeletes = "use SoftDeletes;";
            $traits[] = 'SoftDeletes';
        }

        // Vemos si hay factory
        $factoryGenerator = '';
        if ($module->has_fake_data) {
            $includes .= "use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;\r\n";
            $includes .= "use Database\\Factories\\${name}Factory;\r\n";
            $traits[] = 'HasFactory';
            $factoryGenerator = "/**
            * Create a new factory instance for the model.
            *
            * @return \\Illuminate\\Database\\Eloquent\\Factories\\Factory
            */
           protected static function newFactory()
           {
               return new ${name}Factory;
           }";
        }

        // Añadimos las fechas
        $fields = ModuleField::where('crud_module_id', $module->id)
            ->where(function ($q) {
                $q->where('field_type_slug', '=', 'datetime')
                    ->orWhere('field_type_slug', '=', 'date');
            })
            ->get();
        $dates = "";
        $attributes = "";
        if ($fields) {
            $includes .= "use Carbon\Carbon;\r\n";

            $dates .= "protected \$dates = [";
            foreach ($fields as $field) {
                $dates .= "'" . $field->column_name . "',\n";

                // Añadimos los atributos si no son los campos básicos
                $basicDate = array("created_at", "updated_at", "deleted_at");
                if (!in_array($field->column_name, $basicDate)) {
                    $modelAttributePath = $this->crudGenerator
                        ->resourcePath . "Models/attributes/" . $field->field_type_slug . ".stub";
                    $attributes .= str_replace(
                        [
                            '{{__columnName__}}',
                            '{{__columnNamePascalCase__}}'
                        ],
                        [
                            $field->column_name,
                            Str::studly(strtolower($field->column_name)),
                        ],
                        $this->crudGenerator->getStub($modelAttributePath)
                    );
                }
            }
            $dates .= "];";
        }

        // Añadimos campos fillable
        $fields = ModuleField::where('crud_module_id', $module->id)
            ->where('field_type_slug', '<>', 'auto_increment')
            ->where('is_multilang', false)
            ->get();
        $fillables = "";
        if ($fields) {
            $fillables .= "protected \$fillable = [";
            foreach ($fields as $field) {
                switch ($field->field_type_slug) {
                    case "belongsToRelationship":
                        $fillables .= "'" . $field->column_name . "_id',\n";

                        $modelName = explode(DIRECTORY_SEPARATOR, $field->default_value);

                        $modelAttributePath = $this->crudGenerator
                            ->resourcePath . "Models/attributes/" . $field->field_type_slug . ".stub";
                        $attributes .= str_replace(
                            [
                                '{{__columnName__}}',
                                '{{__model__}}'
                            ],
                            [
                                $field->column_name,
                                end($modelName),
                            ],
                            $this->crudGenerator->getStub($modelAttributePath)
                        );

                        break;
                    case "belongsToManyRelationship":
                        // No tiene fillable. Solo tabla pivot
                        $modelName = explode(DIRECTORY_SEPARATOR, $field->default_value);

                        $modelAttributePath = $this->crudGenerator
                            ->resourcePath . "Models/attributes/" . $field->field_type_slug . ".stub";
                        $attributes .= str_replace(
                            [
                                '{{__foreignKey__}}',
                                '{{__columnName__}}',
                                '{{__columnNamePlural__}}',
                                '{{__model__}}'
                            ],
                            [
                                $module->modelLowerCase,
                                $field->column_name,
                                Str::plural($field->column_name),
                                end($modelName)
                            ],
                            $this->crudGenerator->getStub($modelAttributePath)
                        );

                        // Añadimos el use de la clase
                        $nameSpace = ModelSelector::extractNamespace($field->default_value . ".php");
                        $modelName = explode(DIRECTORY_SEPARATOR, $field->default_value);
                        $className = end($modelName);

                        $fullClassName = $nameSpace . "\\" . $className;
                        // Si no existe el use de la clase lo incluimos
                        if (strpos($includes, "use " . $fullClassName . ";") === false) {
                            $includes .= "use " . $fullClassName . ";\r\n";
                        }

                        break;
                    default:
                        $fillables .= "'" . $field->column_name . "',\n";
                }
            }
            $fillables .= "];";
        }

        // Añadimos campos
        $modelPath = $this->crudGenerator->resourcePath . "Models/Model.stub";
        $modelTemplate = str_replace(
            [
                '{{__constSelects__}}',
                '{{__translatable__}}',
                '{{__includes__}}',
                '{{__traits__}}',
                '{{__factoryGenerator__}}',
                '{{__dates__}}',
                '{{__attributes__}}',
                '{{__fillables__}}',
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralUpperCase}}',
                '{{modelTableName}}'
            ],
            [
                $constSelects,
                $langData,
                $includes,
                $traits ? 'use ' . implode(",\n\t\t", $traits) . ';' : '',
                $factoryGenerator,
                $dates,
                $attributes,
                $fillables,
                $name,
                $module->modelLowerCaselPlural,
                $module->modelLowerCase,
                $module->modelPlural,
                $module->tableName
            ],
            $this->crudGenerator->getStub($modelPath)
        );

        $modelDirectory = $this->crudGenerator
            ->destinyPath . DIRECTORY_SEPARATOR . $module->modelPlural . DIRECTORY_SEPARATOR . "Models";

        if (!file_exists($modelDirectory)) {
            mkdir($modelDirectory, 0755, true);
        }

        file_put_contents($modelDirectory . DIRECTORY_SEPARATOR . "{$name}.php", $modelTemplate);

        if ($hasLang > 0) {
            $this->modelTranslation($module);
        }
    }

    protected function modelTranslation(Module $module)
    {
        $name = $module->model;
        $modelPath = $this->crudGenerator->resourcePath . "Models/ModelTranslation.stub";
        $modelTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralUpperCase}}',
                '{{modelTableName}}'
            ],
            [
                $module->model,
                $module->modelLowerCaselPlural,
                $module->modelLowerCase,
                $module->modelPlural,
                $module->tableName
            ],
            $this->crudGenerator->getStub($modelPath)
        );

        $modelDirectory = $this->crudGenerator
            ->destinyPath . DIRECTORY_SEPARATOR . $module->modelPlural . DIRECTORY_SEPARATOR . "Models";

        if (!file_exists($modelDirectory)) {
            mkdir($modelDirectory, null, true);
        }

        file_put_contents($modelDirectory . DIRECTORY_SEPARATOR . "{$name}Translation.php", $modelTemplate);
    }
}
