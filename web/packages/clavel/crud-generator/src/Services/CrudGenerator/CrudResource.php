<?php

namespace Clavel\CrudGenerator\Services\CrudGenerator;

use Clavel\CrudGenerator\Models\Module;
use Clavel\CrudGenerator\Services\CrudGenerator;
use Illuminate\Support\Str;

class CrudResource
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

        $this->resources($this->crudGenerator->module);
    }

    protected function resources(Module $module)
    {
        $name = $module->model;

        $resourcesPath = $this->crudGenerator->resourcePath . "Resources/Resource.stub";
        $resourcesTemplate = str_replace(
            [
                '{{moduleTypeLowerCase}}',
                '{{moduleTypeUpperCase}}',
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralUpperCase}}'
            ],
            [
                $this->moduleTypeLowerCase,
                $this->moduleTypeUpperCase,
                $name,
                $module->modelLowerCaselPlural,
                $module->modelLowerCase,
                $module->modelPlural
            ],
            $this->crudGenerator->getStub($resourcesPath)
        );

        $resourcesDirectory = $this->crudGenerator
            ->destinyPath . DIRECTORY_SEPARATOR . $module->modelPlural . DIRECTORY_SEPARATOR . "Resources";

        if (!file_exists($resourcesDirectory)) {
            mkdir($resourcesDirectory, 0755, true);
        }

        file_put_contents($resourcesDirectory . DIRECTORY_SEPARATOR . $name . "Resource.php", $resourcesTemplate);
    }
}
