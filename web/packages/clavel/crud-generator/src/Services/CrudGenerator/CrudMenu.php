<?php

namespace Clavel\CrudGenerator\Services\CrudGenerator;

use Illuminate\Support\Str;
use Clavel\CrudGenerator\Models\Module;
use Clavel\CrudGenerator\Models\ModuleField;
use Clavel\CrudGenerator\Services\CrudGenerator;

class CrudMenu
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

        $this->menu($this->crudGenerator->module);
    }

    protected function menu(Module $module)
    {
        $modelIcon = "";
        if (!empty($module->icon)) {
            $modelIcon = '<i class="fa ' . $module->icon . '" aria-hidden="true"></i>';
        }

        $name = $module->model;

        $filePath = $this->crudGenerator->resourcePath . "Views/admin/includes/menu/menu.blade.stub";
        $fileTemplate = str_replace(
            [
                '{{moduleTypeLowerCase}}',
                '{{moduleTypeUpperCase}}',
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralUpperCase}}',
                '{{__iconModule__}}'
            ],
            [
                $this->moduleTypeLowerCase,
                $this->moduleTypeUpperCase,
                $name,
                $module->modelLowerCaselPlural,
                $module->modelLowerCase,
                $module->modelPlural,
                $modelIcon
            ],
            $this->crudGenerator->getStub($filePath)
        );

        $filesDirectory = base_path('resources/views/'.$this->moduleTypeLowerCase.'/includes/menu');

        if (!file_exists($filesDirectory)) {
            mkdir($filesDirectory, 0755, true);
        }

        file_put_contents(
            $filesDirectory . DIRECTORY_SEPARATOR . $module->modelLowerCase . ".blade.php",
            $fileTemplate
        );
    }
}
