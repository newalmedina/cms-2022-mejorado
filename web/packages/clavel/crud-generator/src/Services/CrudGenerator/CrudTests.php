<?php

namespace Clavel\CrudGenerator\Services\CrudGenerator;

use Illuminate\Support\Str;
use Clavel\CrudGenerator\Models\Module;
use Clavel\CrudGenerator\Models\ModuleField;
use Clavel\CrudGenerator\Services\CrudGenerator;

class CrudTests
{
    private $crudGenerator = null;
    private $moduleTypeLowerCase = null;
    private $moduleTypeUpperCase = null;

    public function __construct(CrudGenerator $crudGenerator)
    {
        $this->crudGenerator = $crudGenerator;
    }

    public function generate()
    {
        $this->moduleTypeLowerCase = strtolower($this->crudGenerator->module->type->slug);
        $this->moduleTypeUpperCase = Str::ucfirst(strtolower($this->crudGenerator->module->type->slug));


        $this->tests($this->crudGenerator->module);

        if ($this->crudGenerator->module->has_api_crud) {
            $this->testsApi($this->crudGenerator->module);
        }
    }

    protected function tests(Module $module)
    {
        $this->generateTest($module, "Test.php", "tests/Feature/Test.stub");
    }

    protected function testsApi(Module $module)
    {
        $template = "tests/Feature/ApiTest.stub";
        if (!$this->crudGenerator->module->has_api_crud_secure) {
            $template = "tests/Feature/ApiTestNoAuth.stub";
        }

        $this->generateTest($module, "ApiTest.php", $template);
    }

    protected function generateTest(Module $module, $file_name, $stub_file)
    {
        $name = $module->model;

        $requiredArray = [];
        $requiredArrayLang = [];
        $fieldsArray = [];
        $cleanArray = [];

        $fields = ModuleField::where('crud_module_id', $module->id)
            ->where('in_create', true)
            ->orderBy('order_list', 'ASC')
            ->get();


        foreach ($fields as $field) {
            if ($field->column_name == 'created_at' ||
                $field->column_name == 'updated_at' ||
                $field->column_name == 'deleted_at'
            ) {
            } else {
                if ($field->is_multilang) {
                    if ($field->is_required) {

                        $requiredArrayLang[] = '"' . $field->column_name . '"';
                        $fieldsArray[] = '"' . $field->column_name . '" => $' .
                            $module->modelLowerCase . "->" . $field->column_name;

                        $cleanArray[] = '$' . $module->modelLowerCase . "->" . $field->column_name . ' = ""';
                    }
                    switch ($field->field_type_slug) {
                            // case "text":

                            //     break;
                            // case "textarea":

                            //     break;
                        default:
                    }
                } else {
                    switch ($field->field_type_slug) {
                            // case "radio_yes_no":

                            //     break;
                            // case "text":
                            // case "password":

                            //     break;
                            // case "textarea":

                            //     break;
                            // case "email":

                            //     break;
                            // case "checkbox":

                            //     break;
                            // case "number":

                            //     break;
                            // case "float":

                            //     break;
                            // case "money":

                            //     break;
                            // case "radio":
                            // case "select":
                            // case "checkboxMulti":

                            //     break;
                            // case "date":

                            //     break;
                            // case "datetime":

                            //     break;
                            // case "time":

                            //     break;
                            // case "color":

                            //     break;
                        case "belongsToRelationship":
                            break;
                        case "belongsToManyRelationship":
                            break;
                        default:
                            if ($field->is_required) {
                                $requiredArray[] = '"' . $field->column_name . '"';
                                $fieldsArray[] = '"' . $field->column_name . '" => $' .
                                    $module->modelLowerCase . "->" . $field->column_name;

                                $cleanArray[] = '$' . $module->modelLowerCase . "->" . $field->column_name . ' = ""';
                            }
                    }
                }
            }
        }

        $fieldRequired = implode(",\n", $requiredArray);
        $fieldRequiredLang = implode(",\n", $requiredArrayLang);
        $fieldsFields = implode(",\n", $fieldsArray);
        $cleanFields = implode(";\n", $cleanArray);
        if (!Str::of($cleanFields)->endsWith(';')) {
            // Añadimos el ; final
            $cleanFields .= ";";
        }

        // Buscamos un valor clave a buscar. Mejor que sea un text sin formato o un numero
        // ya que es resto pueden tener modificadores
        $key = "";
        $keyFaker = "";

        $keyField = ModuleField::select('column_name', 'field_type_slug')
            ->where('crud_module_id', $module->id)
            ->where('in_create', true)
            ->where('is_multilang', false)
            ->whereIn('field_type_slug', ['text', 'number'])
            ->orderBy('field_type_slug', 'ASC')
            ->first();

        if (!empty($keyField)) {
            $key = $keyField->column_name;

            $keyFaker = "name";
            if($keyField->field_type_slug == 'number') {
                $keyFaker = "numberBetween(1,999)";
            }
        }

        $requestPath = $this->crudGenerator->resourcePath . $stub_file;
        $requestTemplate = str_replace(
            [
                '{{moduleTypeLowerCase}}',
                '{{moduleTypeUpperCase}}',
                '{{__fieldRequired__}}',
                '{{__fieldRequiredLang__}}',
                '{{__fieldsFields__}}',
                '{{__cleanFields__}}',
                '{{__key__}}',
                '{{__keyFaker__}}',
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralUpperCase}}'
            ],
            [
                $this->moduleTypeLowerCase,
                $this->moduleTypeUpperCase,
                $fieldRequired,
                $fieldRequiredLang,
                $fieldsFields,
                $cleanFields,
                $key,
                $keyFaker,
                $name,
                $module->modelLowerCaselPlural,
                $module->modelLowerCase,
                $module->modelPlural
            ],
            $this->crudGenerator->getStub($requestPath)
        );


        $requestsDirectory = $this->crudGenerator->destinyPath . DIRECTORY_SEPARATOR .
            $module->modelPlural . DIRECTORY_SEPARATOR . "Tests" . DIRECTORY_SEPARATOR . "Feature";

        if (!file_exists($requestsDirectory)) {
            mkdir($requestsDirectory, 0755, true);
        }

        file_put_contents($requestsDirectory . DIRECTORY_SEPARATOR .
            $module->modelPlural . $file_name, $requestTemplate);
    }
}
