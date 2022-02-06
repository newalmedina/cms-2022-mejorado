<?php

namespace Clavel\CrudGenerator\Services;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\File;
use Clavel\CrudGenerator\Models\Module;
use Illuminate\Support\Facades\Storage;
use Clavel\CrudGenerator\Models\ModuleField;

class ExportImportModuleService
{

    protected $module_id;
    protected $basePath = "/exportimport/crud/";
    protected $directorioExportacion;
    protected $guid;

    public function export($module_id)
    {
        $this->module_id = $module_id;

        $this->guid = Uuid::uuid4()->toString();
        $this->directorioExportacion = $this->basePath . $this->guid . "/";

        $module = Module::findOrFail($this->module_id);

        // Exportamos cada uno de los elementos
        $data = $this->exportModule($module);
        if (empty($data)) {
            return false;
        }

        // Exportamos los datos
        Storage::disk('local')->put(
            $this->directorioExportacion.$this->guid."_crud.json",
            json_encode($data, JSON_PRETTY_PRINT)
        );

        return $this->generateZip();

    }

    public function import($zip_file)
    {
        if (!empty($zip_file)) {
            Storage::disk('local')->put(
                $this->basePath . "/" . $zip_file->getClientOriginalName(),
                File::get($zip_file)
            );

            if (!$this->extractZip($zip_file->getClientOriginalName())) {
                return false;
            }

            // Importamos cada uno de los elementos
            if (!$this->importModule()) {
                return false;
            }

        } else {
            return false;
        }
        return true;
    }

    private function exportModule(Module $module)
    {
        try {
            // Module y traducciones
            $moduleExport = $module->toArray();

            $fields = $this->exportFields($module);

            $moduleExport['fields'] = $fields;

            return $moduleExport;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function exportFields(Module $module)
    {
        try {
            // Recorro los fields del modulo
            $fields = [];
            foreach ($module->fields as $field) {
                $fieldExport = $field->toArray();

                $fields[] = $fieldExport;
            }

            return $fields;
        } catch (\Exception $e) {
            return false;
        }
    }


    private function importModule()
    {
        $dir_import = $this->basePath . $this->guid;
        $filePath = $dir_import . "/" . $this->guid . "_crud.json";
        if (!Storage::disk('local')->exists($filePath)) {
            return false;
        }

        try {
            $moduleData = Storage::disk('local')->get($filePath);
            $moduleJson = json_decode($moduleData, true);

            // Nuevos valores
            $module = new Module();

            $module->title = $moduleJson['title']." Copia";
            $module->model = $moduleJson['model'];
            $module->model_plural = $moduleJson['model_plural'];
            $module->table_name = $moduleJson['table_name'];
            $module->icon = $moduleJson['icon'];
            $module->active = $moduleJson['active'];
            $module->has_soft_deletes = $moduleJson['has_soft_deletes'];
            $module->has_api_crud = $moduleJson['has_api_crud'];
            $module->has_api_crud_secure = $moduleJson['has_api_crud_secure'];
            $module->has_create_form = $moduleJson['has_create_form'];
            $module->has_edit_form = $moduleJson['has_edit_form'];
            $module->has_show_form = $moduleJson['has_show_form'];
            $module->has_delete_form = $moduleJson['has_delete_form'];
            $module->has_exports = $moduleJson['has_exports'];
            $module->entries_page = $moduleJson['entries_page'];
            $module->order_by_field = $moduleJson['order_by_field'];
            $module->order_direction = $moduleJson['order_direction'];
            $module->has_fake_data = $moduleJson['has_fake_data'];
            $module->theme_id = $moduleJson['theme_id'];
            $module->type_id = $moduleJson['type_id'];

            $module->save();

            // Importar fields
            $fields = $moduleJson['fields'];
            if (!$this->importFields($module, $fields)) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    private function importFields(Module $module, $fields)
    {
        $dir_import = $this->basePath . $this->guid;

        try {
            foreach ($fields as $field) {
                $new_field = new ModuleField();
                $new_field->crud_module_id = $module->id;


                $new_field->order_list = $field['order_list'];
                $new_field->order_create = $field['order_create'];
                $new_field->field_type_slug = $field['field_type_slug'];
                $new_field->column_name = $field['column_name'];
                $new_field->column_title = $field['column_title'];
                $new_field->column_tooltip = $field['column_tooltip'];
                $new_field->in_list = $field['in_list'];
                $new_field->in_create = $field['in_create'];
                $new_field->in_edit = $field['in_edit'];
                $new_field->in_show = $field['in_show'];
                $new_field->is_required = $field['is_required'];
                $new_field->is_multilang = $field['is_multilang'];
                $new_field->can_modify = $field['can_modify'];
                $new_field->data = $field['data'];
                $new_field->min_length = $field['min_length'];
                $new_field->max_length = $field['max_length'];
                $new_field->default_value = $field['default_value'];
                $new_field->use_editor = $field['use_editor'];

                $new_field->save();


            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function generateZip()
    {
        $zip_name = $this->guid . '.zip';
        $zip_file = storage_path() . "/app" . $this->basePath . $zip_name;
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE);

        $path = storage_path('app' . $this->directorioExportacion);
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();

                // extracting filename with substr/strlen
                $relativePath = str_replace($path, "", $filePath);

                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();
        return response()->download($zip_file);
    }


    private function extractZip($filename)
    {
        $zip_file = storage_path() . "/app" . $this->basePath . $filename;
        $this->guid = str_replace(".zip", "", $filename);
        $dir_extract = storage_path() . "/app" . $this->basePath . $this->guid;
        $zip = new \ZipArchive();
        $x = $zip->open($zip_file);
        if ($x === true) {
            $zip->extractTo($dir_extract);
            $zip->close();
        }

        return true;
    }

}
