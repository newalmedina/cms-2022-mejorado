<?php

namespace Clavel\CrudGenerator\Services\CrudGenerator;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Clavel\CrudGenerator\Models\Module;
use Clavel\CrudGenerator\Models\ModuleGrid;
use Clavel\CrudGenerator\Models\ModuleField;
use Clavel\CrudGenerator\Services\CrudGenerator;

class CrudView
{
    private $crudGenerator = null;
    private $moduleTypeLowerCase = null;
    private $moduleTypeUpperCase = null;


    private $scriptsStyles = "";
    private $scriptsIncludes = "";
    private $scriptsData = "";
    private $contentAdditional = "";
    private $needIncludesDateTime = false;
    private $needIncludesColor = false;
    private $needIncludesSelect = false;
    private $fieldData = "";

    public function __construct(CrudGenerator $crudGenerator)
    {
        $this->crudGenerator = $crudGenerator;
    }

    public function generate()
    {
        $this->moduleTypeLowerCase = strtolower($this->crudGenerator->module->type->slug);
        $this->moduleTypeUpperCase = Str::ucfirst(strtolower($this->crudGenerator->module->type->slug));

        $this->generateIndexView($this->crudGenerator->module);
        $this->generateEditView($this->crudGenerator->module);
    }

    protected function generateIndexView(Module $module)
    {
        $name = $module->model;
        $viewsPath = $this->crudGenerator->resourcePath . "Views/index.blade.stub";

        // Leemos los campos a generar dinamicamente
        $fieldsModule = ModuleField::where('crud_module_id', $module->id)
            ->where('in_list', true)
            ->where('column_name', '<>', 'id')
            ->orderBy('order_list', 'ASC')
            ->get();

        $hasActive = false;
        $tableHeads = "";
        $fields = [];
        $i = 0;
        foreach ($fieldsModule as $field) {
            if ($field->column_name == 'active') {
                $hasActive = true;
                $tableHeads .= "<th scope=\"col\">\n";
                $fields[$i++] = '
                    {
                        "title"         : "{!! trans(\'{{modelNamePluralUpperCase}}::' .
                    '{{modelNamePluralLowerCase}}/' . $this->moduleTypeLowerCase . '_lang.fields.' . $field->column_name . '\') !!}",
                        orderable       : false,
                        searchable      : false,
                        data            : \'' . $field->column_name . '\',
                        sWidth          : \'50px\'
                    }';
            } else {
                switch ($field->field_type_slug) {
                    case "radio_yes_no":
                        $fields[$i++] = '
                        {
                            "title"         : "{!! trans(\'{{modelNamePluralUpperCase}}::' .
                            '{{modelNamePluralLowerCase}}/' . $this->moduleTypeLowerCase . '_lang.fields.' . $field->column_name . '\') !!}",
                            orderable       : false,
                            searchable      : false,
                            data            : \'' . $field->column_name . '\',
                            sWidth          : \'50px\'
                        }';
                        $tableHeads .= "<th scope=\"col\">\n";
                        break;
                    case "checkbox":
                        $fields[$i++] = '
                            {
                                "title"         : "{!! trans(\'{{modelNamePluralUpperCase}}::' .
                            '{{modelNamePluralLowerCase}}/' . $this->moduleTypeLowerCase . '_lang.fields.' . $field->column_name . '\') !!}",
                                orderable       : true,
                                searchable      : false,
                                data            : \'' . $field->column_name . '\',
                                name            : \'c' . '.' . $field->column_name . '\',
                                sWidth          : \'80px\'
                            }';
                        $tableHeads .= "<th scope=\"col\">\n";
                        break;

                    case "text":
                    case "select":
                        $fields[$i++] = '
                        {
                                "title"         : "{!! trans(\'{{modelNamePluralUpperCase}}::' .
                            '{{modelNamePluralLowerCase}}/' . $this->moduleTypeLowerCase . '_lang.fields.' . $field->column_name . '\') !!}",
                                orderable       : true,
                                searchable      : true,
                                data            : \'' . $field->column_name . '\',
                                name            : \'c' . ($field->is_multilang ? "t" : "") . '.' .
                            $field->column_name . '\',
                                sWidth          : \'\'
                            }
                        ';
                        $tableHeads .= "<th scope=\"col\">\n";
                        break;
                    case "textarea":
                        $fields[$i++] = '';
                        break;
                }
            }
        }

        $fieldsData = implode(',', $fields) . ",";

        $changeState = "";
        if ($hasActive) {
            $activePath = $this->crudGenerator->resourcePath . "Views/controls/changeState.stub";
            $changeState = $this->crudGenerator->getStub($activePath);
        }

        // Tiene exportaciones Excel
        $excelExports = "";
        if ($module->has_exports) {
            $excelPath =  $this->crudGenerator->resourcePath . "Views/excel.stub";
            $excelExports = $this->crudGenerator->getStub($excelPath);
        }

        // Generamos el fichero final
        $viewsTemplate = str_replace(
            [
                '{{__tableHeads__}}',
                '{{__changeState__}}',
                '{{__fields__}}',
                '{{__excelExports__}}',
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralUpperCase}}',
                '{{moduleTypeLowerCase}}',
                '{{moduleTypeUpperCase}}'
            ],
            [
                $tableHeads,
                $changeState,
                $fieldsData,
                $excelExports,
                $name,
                $module->modelLowerCaselPlural,
                $module->modelLowerCase,
                $module->modelPlural,
                $this->moduleTypeLowerCase,
                $this->moduleTypeUpperCase
            ],
            $this->crudGenerator->getStub($viewsPath)
        );

        $viewsDirectory = $this->crudGenerator
            ->destinyPath . DIRECTORY_SEPARATOR . $module->modelPlural . DIRECTORY_SEPARATOR . "Views";

        if (!file_exists($viewsDirectory)) {
            mkdir($viewsDirectory, 0755, true);
        }

        file_put_contents($viewsDirectory . DIRECTORY_SEPARATOR . $this->moduleTypeLowerCase . "_index.blade.php", $viewsTemplate);
    }

    protected function generateEditView(Module $module)
    {
        $grid_style = $this->getGridStyle($module->id);

        $this->generateEditViewNoLang($module, $grid_style);
        $this->generateEditViewLang($module, $grid_style);
    }

    private function getGridStyle($module_id)
    {
        // Vemos si tenemos una grid o bien lo ponemos todo inline
        $gridFields = ModuleGrid::where('crud_module_id', $module_id)
            ->count();

        $grid_style = 'inline';
        if ($gridFields > 0) {
            $grid_style = 'grid';
        }

        return $grid_style;
    }

    protected function generateEditViewNoLang(Module $module, $grid_style)
    {
        $name = $module->model;

        $this->scriptsStyles = "";
        $this->scriptsIncludes = "";
        $this->scriptsData = "";
        $this->contentAdditional = "";
        $this->needIncludesDateTime = false;
        $this->needIncludesColor = false;
        $this->needIncludesSelect = false;
        $this->fieldData = "";

        // Añadimos los estilos y los scripts por defecto
        $this->scriptsStyles .= '';
        $this->scriptsIncludes .= '';

        // Leemos los campos a generar dinamicamente que no son multiidioma


        if ($grid_style == 'inline') {
            $this->generateInlineForm($module, $grid_style);
        } else if ($grid_style == 'grid') {
            $this->generateGridForm($module, $grid_style);
        }



        // Ahora añadimos los extras

        // Si hemos añadido algun campo de tipo fecha, hora, añadimos los includes pertinentes
        if ($this->needIncludesDateTime) {
            $this->scriptsStyles .= '<link
            href="{{ asset(\'/assets/' . $this->moduleTypeLowerCase . '/vendor/datetimepicker/css/bootstrap-datetimepicker.min.css\') }}"
            rel="stylesheet" type="text/css" />';
            $this->scriptsStyles .= '<link rel=“stylesheet” href="{{ asset("assets/admin/vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css") }}">';
            $this->scriptsIncludes .= '<script
            src="{{ asset(\'/assets/' . $this->moduleTypeLowerCase . '/vendor/moment/moment.min.js\')}}">
            </script>' .
                '<script
                src="{{ asset(\'/assets/' . $this->moduleTypeLowerCase . '/vendor/datetimepicker/js/bootstrap-datetimepicker.min.js\')}}">
                </script>';
            $this->scriptsIncludes .= '<script type=“text/javascript” src="{{ asset("assets/admin/vendor/moment/js/locales.min.js") }}"></script>';
            $this->scriptsIncludes .= '<script type=“text/javascript” src="{{ asset("assets/admin/vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js") }}"></script>';
        }
        if ($this->needIncludesColor) {
            $this->scriptsStyles .= '<link
            href="{{ asset("/assets/' . $this->moduleTypeLowerCase . '/vendor/colorpicker/css/bootstrap-colorpicker.min.css") }}"
            rel="stylesheet" type="text/css" />';
            $this->scriptsIncludes .= '<script type="text/javascript"
            src="{{ asset(\'/assets/' . $this->moduleTypeLowerCase . '/vendor/colorpicker/js/bootstrap-colorpicker.min.js\')}}">
            </script>';
        }
        // Si hemos añadido algun campo de tipo select, añadimos los includes pertinentes
        if ($this->needIncludesSelect) {
            $this->scriptsStyles .= '<link href="{{ asset("/assets/' . $this->moduleTypeLowerCase . '/vendor/select2/css/select2.min.css") }}"
            rel="stylesheet" type="text/css" />';
            $this->scriptsIncludes .= '<script type="text/javascript" src="{{ asset("/assets/admin/vendor/select2/js/select2.full.js") }}" type="text/javascript"></script>';
        }

        // Si tiene textarea tenemos que añadir los scripts del editor
        $textareaCount = ModuleField::where('crud_module_id', $module->id)
            ->where('in_edit', true)
            ->where('field_type_slug', 'textarea')
            ->where('use_editor', '=', 'tiny')
            ->count();

        if ($textareaCount > 0) {
            $this->scriptsIncludes .= '<script type="text/javascript"
            src="{{ asset("assets/' . $this->moduleTypeLowerCase . '/vendor/tinymce/tinymce.min.js") }}">
            </script>';
            $scriptsPath = $this->crudGenerator->resourcePath . "Views/controls/textareaScript.stub";
            $this->scriptsData .= $this->crudGenerator->getStub($scriptsPath);

            // Añadimos el contenido adicional que es el pop-up del buscador de archivos
            $ca = $this->crudGenerator->resourcePath . "Views/controls/textareaAdditional.stub";
            $this->contentAdditional .=  $this->crudGenerator->getStub($ca);
        }

        // Cargamos el stub de la edicion de campos sin edicion
        $viewsPath = $this->crudGenerator->resourcePath . "Views/edit.blade.stub";
        $viewsTemplate = $this->crudGenerator->getStub($viewsPath);

        // Ahora ponemos los textos de los campos según el modelo
        $viewsTemplate = str_replace(
            [
                '{{moduleTypeLowerCase}}',
                '{{moduleTypeUpperCase}}',
                '{{__fields__}}',
                '{{__scriptsIncludes__}}',
                '{{__scriptsStyles__}}',
                '{{__scriptsData__}}',
                '{{__contentAdditional__}}',
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralUpperCase}}'
            ],
            [
                $this->moduleTypeLowerCase,
                $this->moduleTypeUpperCase,
                $this->fieldData,
                $this->scriptsIncludes,
                $this->scriptsStyles,
                $this->scriptsData,
                $this->contentAdditional,
                $name,
                $module->modelLowerCaselPlural,
                $module->modelLowerCase,
                $module->modelPlural
            ],
            $viewsTemplate
        );

        $viewsDirectory = $this->crudGenerator
            ->destinyPath . DIRECTORY_SEPARATOR . $module->modelPlural . DIRECTORY_SEPARATOR . "Views";

        if (!file_exists($viewsDirectory)) {
            mkdir($viewsDirectory, 0755, true);
        }

        file_put_contents($viewsDirectory . DIRECTORY_SEPARATOR . $this->moduleTypeLowerCase . "_edit.blade.php", $viewsTemplate);
    }

    protected function generateEditViewLang(Module $module, $grid_style)
    {
        $name = $module->model;

        $this->scriptsStyles = "";
        $this->scriptsIncludes = "";
        $this->fieldData = "";
        // Leemos los campos a generar dinamicamente que son multiidioma
        $fields = ModuleField::where('crud_module_id', $module->id)
            ->where('in_edit', true)
            ->where('is_multilang', true)
            ->orderBy('order_create', 'ASC')
            ->get();

        // si no hay nada volvemos
        if ($fields->count() == 0) {
            return;
        }


        foreach ($fields as $field) {
            // Para cada campo leemos su stub y lo rellenamos
            $fieldPath = $this->crudGenerator
                ->resourcePath . "Views/form_components/{$grid_style}/{$field->field_type_slug}_lang.stub";
            $fieldTemplate = $this->crudGenerator->getStub($fieldPath);
            $fieldTemplate = str_replace('{{fieldName}}', $field->column_name, $fieldTemplate);
            switch ($field->field_type_slug) {
                case "select":
                    $data = $field->data;
                    if (!empty($data)) {
                        $someArray = json_decode($data, true);

                        $arrayValores = [];
                        foreach ($someArray as $key => $value) {
                            $arrayValores[] = "'" . $key . "' => '" . $value . "'";
                        }

                        $constSelects = implode(",\n", $arrayValores);
                        $fieldTemplate = str_replace('{{__optionsSelect__}}', $constSelects, $fieldTemplate);
                    }
                    break;
            }
            $this->fieldData .= $fieldTemplate;
        }

        // Cargamos el stub de la edicion de campos sin edicion
        $viewsPath = $this->crudGenerator->resourcePath . "Views/edit_lang.blade.stub";
        $viewsTemplate = $this->crudGenerator->getStub($viewsPath);

        // Ahora ponemos los textos de los campos según el modelo
        $viewsTemplate = str_replace(
            [
                '{{moduleTypeLowerCase}}',
                '{{moduleTypeUpperCase}}',
                '{{__fields__}}',
                '{{__scriptsIncludes__}}',
                '{{__scriptsStyles__}}',
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralUpperCase}}'
            ],
            [
                $this->moduleTypeLowerCase,
                $this->moduleTypeUpperCase,
                $this->fieldData,
                $this->scriptsIncludes,
                $this->scriptsStyles,
                $name,
                $module->modelLowerCaselPlural,
                $module->modelLowerCase,
                $module->modelPlural
            ],
            $viewsTemplate
        );

        $viewsDirectory = $this->crudGenerator
            ->destinyPath . DIRECTORY_SEPARATOR . $module->modelPlural . DIRECTORY_SEPARATOR . "Views";

        if (!file_exists($viewsDirectory)) {
            mkdir($viewsDirectory, 0755, true);
        }

        file_put_contents($viewsDirectory . DIRECTORY_SEPARATOR . $this->moduleTypeLowerCase . "_edit_lang.blade.php", $viewsTemplate);
    }


    private function generateInlineForm(Module $module, $grid_style)
    {
        $fields = ModuleField::where('crud_module_id', $module->id)
            ->where('in_edit', true)
            ->where('is_multilang', false)
            ->orderBy('order_create', 'ASC')
            ->get();


        foreach ($fields as $field) {
            $this->fieldData .= $this->getComponent($field, $grid_style);
        }
    }

    private function generateGridForm(Module $module, $grid_style)
    {
        // Leemos las plantillas de la grid
        $gridPath = $this->crudGenerator->resourcePath .
            "Views/form_components/{$grid_style}/";

        $grid_row_open = $this->crudGenerator->getStub($gridPath."grid_row_open.stub");
        $grid_row_close = $this->crudGenerator->getStub($gridPath."grid_row_close.stub");
        $grid_row_cell = $this->crudGenerator->getStub($gridPath."grid_row_cell.stub");


        // Leemos todos las filas y las columnas
        $gridFields = ModuleGrid::select(
                [
                    'crud_module_grid.row',
                    'crud_module_grid.col',
                    'crud_module_grid.max_col',
                    'crud_module_grid.crud_module_field_id',
                    'mf.field_type_slug',
                    'mf.column_name'
                ]
            )
            ->join('crud_module_fields as mf', function ($join) {
                $join->on('mf.crud_module_id',  'crud_module_grid.crud_module_id');
                $join->on('mf.id', 'crud_module_grid.crud_module_field_id' );
            })
            ->where('crud_module_grid.crud_module_id', $module->id)
            ->where('mf.in_edit', true)
            ->where('mf.is_multilang', false)
            ->orderBy('row', 'ASC')
            ->orderBy('col', 'ASC')
            ->get();

        $row = 0;
        $col_number = 1;
        foreach ($gridFields as $field) {

            // Cerramos el anterior bloque si fuese necesario
            if($row > 0 && $field->row != $row) {
                $this->fieldData .= $grid_row_close;
            }

            // Abrimos bloque si fuese necesario
            if($field->row != $row) {
                $this->fieldData .= $grid_row_open;

                $row = $field->row;
                $col_number = 1;

            }

            // Ahora añadimos la columna
            $component = $this->getComponent($field, $grid_style);
            $this->fieldData .= str_replace(
                [
                    '{{component}}',
                    '{{row}}',
                    '{{col}}',
                    '{{max-col}}',
                ],
                [
                    $component,
                    $field->row,
                    $field->col,
                    (12 / $field->max_col)

                ],
                $grid_row_cell
            );
        }

        // Cerramos el ultimo bloque
        $this->fieldData .= $grid_row_close;
    }

    private function getComponent($field, $grid_style)
    {
        // Para cada campo leemos su stub y lo rellenamos
        $fieldPath = $this->crudGenerator->resourcePath .
            "Views/form_components/{$grid_style}/{$field->field_type_slug}.stub";
        $fieldTemplate = $this->crudGenerator->getStub($fieldPath);
        $fieldTemplate = str_replace(
            [
                '{{moduleTypeLowerCase}}',
                '{{moduleTypeUpperCase}}',
                '{{fieldName}}',
                '{{__constFieldName__}}',
                '{{fieldNamePlural}}',
            ],
            [
                $this->moduleTypeLowerCase,
                $this->moduleTypeUpperCase,
                $field->column_name,
                strtoupper($field->column_name),
                Str::plural($field->column_name)
            ],
            $fieldTemplate
        );

        // Verificamos si tenemos que añadir scripts segun el tipo de campo
        switch ($field->field_type_slug) {
            case "datetime":
            case "date":
            case "time":
                $this->needIncludesDateTime = true;
                $scriptsDatePath = $this->crudGenerator->resourcePath .
                    "Views/controls/" . $field->field_type_slug . ".stub";
                $this->scriptsData .= str_replace(
                    [
                        '{{moduleTypeLowerCase}}',
                        '{{moduleTypeUpperCase}}',
                        '{{__columnName__}}',
                        '{{__columnNameFirstCapital__}}'
                    ],
                    [
                        $this->moduleTypeLowerCase,
                        $this->moduleTypeUpperCase,
                        $field->column_name,
                        Str::ucfirst(strtolower($field->column_name)),
                    ],
                    $this->crudGenerator->getStub($scriptsDatePath)
                );
                break;
            case "image":
            case "file":
                $scriptsImagePath = $this->crudGenerator->resourcePath .
                    "Views/controls/" . $field->field_type_slug . ".stub";
                $this->scriptsData .= str_replace(
                    [
                        '{{moduleTypeLowerCase}}',
                        '{{moduleTypeUpperCase}}',
                        '{{__columnName__}}',
                        '{{__columnNameFirstCapital__}}'
                    ],
                    [
                        $this->moduleTypeLowerCase,
                        $this->moduleTypeUpperCase,
                        $field->column_name,
                        Str::ucfirst(strtolower($field->column_name)),
                    ],
                    $this->crudGenerator->getStub($scriptsImagePath)
                );
                break;
            case "color":
                $this->needIncludesColor = true;
                $scriptsDatePath = $this->crudGenerator->resourcePath .
                    "Views/controls/" . $field->field_type_slug . ".stub";
                $this->scriptsData .= str_replace(
                    [
                        '{{moduleTypeLowerCase}}',
                        '{{moduleTypeUpperCase}}',
                        '{{__columnName__}}',
                        '{{__columnNameFirstCapital__}}'
                    ],
                    [
                        $this->moduleTypeLowerCase,
                        $this->moduleTypeUpperCase,
                        $field->column_name,
                        Str::ucfirst(strtolower($field->column_name)),
                    ],
                    $this->crudGenerator->getStub($scriptsDatePath)
                );
                break;
            case "select":
                $this->needIncludesSelect = true;
                break;
        }

        return $fieldTemplate;
    }
}
