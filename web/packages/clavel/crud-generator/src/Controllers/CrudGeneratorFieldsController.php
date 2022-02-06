<?php

namespace Clavel\CrudGenerator\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Clavel\CrudGenerator\Models\Module;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminController;
use Clavel\CrudGenerator\Models\FieldType;
use Clavel\CrudGenerator\Models\ModuleGrid;
use Clavel\CrudGenerator\Models\ModuleField;
use Clavel\CrudGenerator\Requests\FieldRequest;
use Clavel\CrudGenerator\Services\ModelSelector;

class CrudGeneratorFieldsController extends AdminController
{
    protected $page_title_icon = '<i class="fa fa-archive"></i>';

    public function __construct()
    {
        parent::__construct();

        $this->access_permission = 'admin-modulos-crud';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($module_id)
    {
        // Si no tiene permisos para ver el listado lo echa.
        if (!auth()->user()->isAbleTo('admin-modulos-crud-list')) {
            app()->abort(403);
        }

        $page_title = trans("crud-generator::fields/admin_lang.title");

        $module = Module::find($module_id);
        if (empty($module)) {
            app()->abort(500);
        }

        $this->verifyBasicFields($module);

        return view(
            "crud-generator::fields.admin_index",
            compact(
                'page_title',
                'module'
            )
        )->with('page_title_icon', $this->page_title_icon);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($module_id)
    {
        // Si no tiene permisos para ver el listado lo echa.
        if (!auth()->user()->isAbleTo('admin-modulos-crud-create')) {
            app()->abort(403);
        }

        $module = Module::find($module_id);
        if (empty($module)) {
            app()->abort(500);
        }

        $field = new ModuleField();
        $form_data = array(
            'route' => array('admin.crud-generator.fields.store', $module->id),
            'method' => 'POST',
            'id' => 'formData',
            'class' => 'form-horizontal'
        );
        $page_title = trans("crud-generator::fields/admin_lang.new");

        $fieldTypes = FieldType::actives()->get();

        return view(
            'crud-generator::fields.admin_edit',
            compact(
                'page_title',
                'module',
                'field',
                'fieldTypes',
                'form_data'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($module_id, FieldRequest $request)
    {
        if (!auth()->user()->isAbleTo('admin-modulos-crud-create')) {
            app()->abort(403);
        }

        $module = Module::find($module_id);
        if (empty($module)) {
            app()->abort(500);
        }

        $field = new ModuleField();
        $this->saveData($module, $field, $request);

        return redirect()->to('admin/crud-generator/' . $module->id . '/fields/' . $field->id . '/edit')
            ->with('success', trans('crud-generator::fields/admin_lang.save_ok'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($module_id, $id)
    {
        if (!auth()->user()->isAbleTo('admin-modulos-crud-update')) {
            app()->abort(403);
        }

        $module = Module::find($module_id);
        if (empty($module)) {
            app()->abort(500);
        }


        $field = ModuleField::find($id);

        $form_data = array(
            'route' => array('admin.crud-generator.fields.update', $module->id, $field->id),
            'method' => 'PATCH',
            'id' => 'formData',
            'class' => 'form-horizontal'
        );
        $page_title = trans("crud-generator::fields/admin_lang.modify");

        $fieldTypes = FieldType::actives()->get();

        return view(
            'crud-generator::fields.admin_edit',
            compact(
                'page_title',
                'module',
                'field',
                'fieldTypes',
                'form_data'
            )
        );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FieldRequest $request, $module_id, $id)
    {
        if (!auth()->user()->isAbleTo('admin-modulos-crud-update')) {
            app()->abort(403);
        }

        $module = Module::find($module_id);
        if (empty($module)) {
            app()->abort(500);
        }

        $field = ModuleField::find($id);
        if (empty($field)) {
            app()->abort(500);
        }
        $this->saveData($module, $field, $request);

        return redirect()->to('admin/crud-generator/' . $module->id . '/fields/' . $field->id . '/edit')
            ->with('success', trans('crud-generator::fields/admin_lang.save_ok'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($module_id, $id)
    {
        // Si no tiene permisos para modificar lo echamos
        if (!auth()->user()->isAbleTo('admin-modulos-crud-delete')) {
            app()->abort(403);
        }

        $module = Module::find($module_id);
        if (empty($module)) {
            app()->abort(500);
        }

        $field = ModuleField::find($id);
        if (empty($field)) {
            app()->abort(500);
        }

        $field->delete();

        return response()->json(array(
            'success' => true,
            'msg' => trans('crud-generator::fields/admin_lang.delete'),
            'id' => $field->id
        ));
    }


    public function getData($module_id)
    {
        $module = Module::find($module_id);
        if (empty($module)) {
            app()->abort(500);
        }

        $fields = ModuleField::select(
            array(
                'crud_module_fields.id',
                'column_name',
                'column_title',
                'can_modify',
                'crud_field_types.name',
                'is_multilang',
                'in_list',
                'in_create',
                'in_edit',
                'in_show',
                'is_required'
            )
        )
            ->join("crud_field_types", "crud_field_types.slug", "=", "crud_module_fields.field_type_slug")

            ->where('crud_module_id', $module->id)
            ->orderBy('order_list');

        return Datatables::of($fields)
            ->addColumn('actions', function ($data) use ($module) {
                $actions = '';
                if (auth()->user()->isAbleTo("admin-modulos-crud-update") && $data->can_modify) {
                    $actions .= '<button class="btn btn-primary btn-sm" onclick="javascript:window.location=\'' .
                        url('admin/crud-generator/' . $module->id . '/fields/' . $data->id . '/edit') . '\';"
                        data-content="' .
                        trans('general/admin_lang.modificar') . '" data-placement="right" data-toggle="popover">
                        <i class="fas fa-edit" aria-hidden="true"></i></button> ';
                }
                if (auth()->user()->isAbleTo("admin-modulos-crud-delete") && $data->can_modify) {
                    $actions .= '<button class="btn btn-danger btn-sm" onclick="javascript:deleteElement(\'' .
                        url('admin/crud-generator/' . $module->id . '/fields/' . $data->id) . '\');" data-content="' .
                        trans('general/admin_lang.borrar') . '" data-placement="left" data-toggle="popover">
                        <i class="fa fa-trash" aria-hidden="true"></i></button>';
                }
                return $actions;
            })
            ->editColumn('is_multilang', function ($data) {
                return '<i class="fa ' . ($data->is_multilang ? "fa-check text-success" : "fa-times text-red") .
                    ' " aria-hidden="true"></i>';
            })
            ->editColumn('in_list', function ($data) {
                return '<i class="fa ' . ($data->in_list ? "fa-check text-success" : "fa-times text-red") .
                    ' " aria-hidden="true"></i>';
            })
            ->editColumn('in_create', function ($data) {
                return '<i class="fa ' . ($data->in_create ? "fa-check text-success" : "fa-times text-red") .
                    ' " aria-hidden="true"></i>';
            })
            ->editColumn('in_edit', function ($data) {
                return '<i class="fa ' . ($data->in_edit ? "fa-check text-success" : "fa-times text-red") .
                    ' " aria-hidden="true"></i>';
            })
            ->editColumn('in_show', function ($data) {
                return '<i class="fa ' . ($data->in_show ? "fa-check text-success" : "fa-times text-red") .
                    ' " aria-hidden="true"></i>';
            })
            ->editColumn('is_required', function ($data) {
                return '<i class="fa ' . ($data->is_required ? "fa-check text-success" : "fa-times text-red") .
                    ' " aria-hidden="true"></i>';
            })
            ->removeColumn('id')
            ->removeColumn('can_modify')
            ->rawColumns([
                'actions',
                'is_multilang',
                'in_list',
                'in_create',
                'in_edit',
                'in_show',
                'is_required'
            ])
            ->make();
    }

    private function saveData(Module $module, ModuleField $field, Request $request)
    {
        // En actualizacion no permitimos cambiar el tipo
        if (empty($field->id)) {
            $field->field_type_slug = $request->get("field_type_slug", "text");
            $field->column_name = str_replace(
                "-",
                "_",
                Str::slug($request->get("column_name", ""), "_")
            );
            $field->crud_module_id = $module->id;
        }

        $field->column_title = $request->get("column_title", "");
        $field->in_list = $request->get("in_list", false);
        $field->in_create = $request->get("in_create", false);
        $field->in_edit = $request->get("in_edit", false);
        $field->in_show = $request->get("in_show", false);
        $field->is_required = $request->get("is_required", false);
        $field->can_modify = true;
        $field->data = $request->get("data", '');
        $field->min_length = $request->get("min_length", null);
        $field->max_length = $request->get("max_length", null);
        $field->default_value = $request->get("default_value", '');
        $field->column_tooltip = $request->get("column_tooltip", '');
        $field->is_multilang = $request->get("is_multilang", false);
        $field->use_editor = $request->get("use_editor", false);

        // $order_list = $request->get("order_list", null);
        // if (empty($order_list)) {
        //     $order_list = ModuleField::where('order_list', '<', 9000)
        //         ->max('order_list') + 1;
        // }
        // $field->order_list = $order_list;


        // $order_create = $request->get("order_create", null);
        // if (empty($order_create)) {
        //     $order_create = ModuleField::where('order_create', '<', 9000)
        //         ->max('order_create') + 1;
        // }
        // $field->order_create = $order_create;

        // Ponemos los radios y los select
        if (
            $field->field_type_slug == "radio" ||
            $field->field_type_slug == "select" ||
            $field->field_type_slug == "checkboxMulti"
        ) {
            $data = [];
            $multi_data = $request->get($field->field_type_slug . "_data", []);
            $multi_value = $request->get($field->field_type_slug . "_value", []);

            for ($i = 0; $i < sizeof($multi_data); $i++) {
                $data[] = [$multi_data[$i], $multi_value[$i]];
            }
            $field->data = json_encode($data);
        }

        $field->save();
    }


    public function getModelFields(Request $request)
    {
        if (!auth()->user()->isAbleTo('admin-modulos-crud-list')) {
            app()->abort(403);
        }

        $columns = [];
        try {
            // Obtenemos el modelo del que queremos obtener sus campos
            $modelPath = $request->get("model", "");
            if (!empty($modelPath)) {
                // Obtenemos el namespace de la clase del modelo
                $nameSpace = ModelSelector::extractNamespace($modelPath . ".php");
                // Obtenemos el nombre del modelo
                $modelName = explode(DIRECTORY_SEPARATOR, $modelPath);
                //$className = end($modelName);
                $className = explode("/", end($modelName))[1];

                // creamos una instancia del modelo para poder acceder a sus datos
                $fullClassName = $nameSpace . "\\" . $className;

                $model = new $fullClassName();
                // Obtenemos las columnas de la base de datos del modelo
                $columnsName = Schema::getColumnListing($model->getTable());

                // Si el modelo uso Translatable, miramos que campos tiene asociados y los añadimos a la lista
                if (!empty($model->translatedAttributes)) {
                    $columnsName = array_merge($columnsName, $model->translatedAttributes);
                }

                foreach ($columnsName as $value) {
                    $columns[$value] = $value;
                }
            }
        } catch (\Exception $ex) {
        }


        return response()->json($columns);
    }

    public function createFull(Request $request, $module_id)
    {
        if (!auth()->user()->isAbleTo('admin-modulos-crud-create')) {
            app()->abort(403);
        }

        try {
            $module = Module::find($module_id);
            if (empty($module)) {
                app()->abort(500);
            }

            ModuleField::where('crud_module_id', $module->id)->delete();

            $this->verifyBasicFields($module);
            $this->extentedFields($module);
        } catch (\Exception $ex) {
        }

        return response()->json(array(
            'success' => true,
            'msg' => trans('crud-generator::fields/admin_lang.fields_created')
        ));
    }

    private function verifyBasicFields(Module $module)
    {
        $fields = ModuleField::where('crud_module_id', $module->id)->count();
        if ($fields == 0) {
            // Creamos los campos básicos

            // ID
            $field = new ModuleField();
            $field->order_list = 0;
            $field->order_create = 0;
            $field->crud_module_id = $module->id;
            $field->field_type_slug = 'auto_increment';
            $field->column_name = 'id';
            $field->column_title = 'ID';
            $field->in_list = true;
            $field->in_create = false;
            $field->in_edit = false;
            $field->in_show = false;
            $field->is_required = true;
            $field->can_modify = false;
            $field->save();

            // Created At
            $field = new ModuleField();
            $field->order_list = 9990;
            $field->order_create = 9990;
            $field->crud_module_id = $module->id;
            $field->field_type_slug = 'datetime';
            $field->column_name = 'created_at';
            $field->column_title = 'Creado el';
            $field->in_list = false;
            $field->in_create = false;
            $field->in_edit = false;
            $field->in_show = false;
            $field->is_required = true;
            $field->can_modify = false;
            $field->save();

            // Modified At
            $field = new ModuleField();
            $field->order_list = 9991;
            $field->order_create = 9991;
            $field->crud_module_id = $module->id;
            $field->field_type_slug = 'datetime';
            $field->column_name = 'updated_at';
            $field->column_title = 'Modificado el';
            $field->in_list = false;
            $field->in_create = false;
            $field->in_edit = false;
            $field->in_show = false;
            $field->is_required = true;
            $field->can_modify = false;
            $field->save();

            if ($module->hasSoftDeletes) {
                // Deleted At
                $field = new ModuleField();
                $field->order_list = 9992;
                $field->order_create = 9992;
                $field->crud_module_id = $module->id;
                $field->field_type_slug = 'datetime';
                $field->column_name = 'deleted_at';
                $field->column_title = 'Borrado el';
                $field->in_list = false;
                $field->in_create = false;
                $field->in_edit = false;
                $field->in_show = false;
                $field->is_required = true;
                $field->can_modify = false;
                $field->save();
            }



            // Otros de prueba

            // Texto
            $field = new ModuleField();
            $field->order_list = 2;
            $field->order_create = 1;
            $field->crud_module_id = $module->id;
            $field->field_type_slug = 'text';
            $field->column_name = 'name';
            $field->column_title = 'Título';
            $field->in_list = true;
            $field->in_create = true;
            $field->in_edit = true;
            $field->in_show = true;
            $field->is_required = true;
            $field->can_modify = true;
            $field->save();

            // Text area
            $field = new ModuleField();
            $field->order_list = 0;
            $field->order_create = 0;
            $field->crud_module_id = $module->id;
            $field->field_type_slug = 'textarea';
            $field->column_name = 'description';
            $field->column_title = 'Descripción';
            $field->in_list = false;
            $field->in_create = true;
            $field->in_edit = true;
            $field->in_show = true;
            $field->is_required = true;
            $field->can_modify = true;
            $field->use_editor = 'tiny';
            $field->save();

            // Radio Yes / No
            $field = new ModuleField();
            $field->order_list = 1;
            $field->order_create = 3;
            $field->crud_module_id = $module->id;
            $field->field_type_slug = 'radio_yes_no';
            $field->column_name = 'active';
            $field->column_title = 'Activo';
            $field->in_list = true;
            $field->in_create = true;
            $field->in_edit = true;
            $field->in_show = true;
            $field->is_required = true;
            $field->can_modify = true;
            $field->save();
        }
    }

    private function extentedFields(Module $module)
    {

        // Password
        $field = new ModuleField();
        $field->order_list = 3;
        $field->order_create = 4;
        $field->crud_module_id = $module->id;
        $field->field_type_slug = 'password';
        $field->column_name = 'password';
        $field->column_title = 'Contraseña';
        $field->in_list = false;
        $field->in_create = true;
        $field->in_edit = true;
        $field->in_show = false;
        $field->is_required = true;
        $field->can_modify = true;
        $field->save();

        // email
        $field = new ModuleField();
        $field->order_list = 4;
        $field->order_create = 5;
        $field->crud_module_id = $module->id;
        $field->field_type_slug = 'email';
        $field->column_name = 'email';
        $field->column_title = 'Email';
        $field->in_list = false;
        $field->in_create = true;
        $field->in_edit = true;
        $field->in_show = false;
        $field->is_required = true;
        $field->can_modify = true;
        $field->save();

        // Checkbox
        $field = new ModuleField();
        $field->order_list = 5;
        $field->order_create = 6;
        $field->crud_module_id = $module->id;
        $field->field_type_slug = 'checkbox';
        $field->column_name = 'aceptado';
        $field->column_title = 'Aceptado';
        $field->in_list = false;
        $field->in_create = true;
        $field->in_edit = true;
        $field->in_show = false;
        $field->is_required = true;
        $field->can_modify = true;
        $field->save();


        // Entero
        $field = new ModuleField();
        $field->order_list = 6;
        $field->order_create = 7;
        $field->crud_module_id = $module->id;
        $field->field_type_slug = 'number';
        $field->column_name = 'edad';
        $field->column_title = 'Edad';
        $field->in_list = false;
        $field->in_create = true;
        $field->in_edit = true;
        $field->in_show = false;
        $field->is_required = true;
        $field->can_modify = true;
        $field->save();


        // Float
        $field = new ModuleField();
        $field->order_list = 7;
        $field->order_create = 8;
        $field->crud_module_id = $module->id;
        $field->field_type_slug = 'float';
        $field->column_name = 'presion';
        $field->column_title = 'Presión';
        $field->in_list = false;
        $field->in_create = true;
        $field->in_edit = true;
        $field->in_show = false;
        $field->is_required = true;
        $field->can_modify = true;
        $field->save();


        // Money
        $field = new ModuleField();
        $field->order_list = 7;
        $field->order_create = 8;
        $field->crud_module_id = $module->id;
        $field->field_type_slug = 'money';
        $field->column_name = 'price';
        $field->column_title = 'Precio';
        $field->in_list = false;
        $field->in_create = true;
        $field->in_edit = true;
        $field->in_show = false;
        $field->is_required = true;
        $field->can_modify = true;
        $field->save();


        // Radio
        $field = new ModuleField();
        $field->order_list = 6;
        $field->order_create = 6;
        $field->crud_module_id = $module->id;
        $field->field_type_slug = 'radio';
        $field->column_name = 'answer';
        $field->column_title = 'Mono selección';
        $field->in_list = false;
        $field->in_create = true;
        $field->in_edit = true;
        $field->in_show = false;
        $field->is_required = true;
        $field->can_modify = true;
        $field->data = '[["0","Si"],["1","No"]]';
        $field->save();

        // Select
        $field = new ModuleField();
        $field->order_list = 7;
        $field->order_create = 7;
        $field->crud_module_id = $module->id;
        $field->field_type_slug = 'select';
        $field->column_name = 'pais';
        $field->column_title = 'País';
        $field->in_list = false;
        $field->in_create = true;
        $field->in_edit = true;
        $field->in_show = false;
        $field->is_required = true;
        $field->can_modify = true;
        $field->data = '[
            ["0","Ninguno"],
        ["1","Espa\u00f1a"],
        ["2","Portugal"],
        ["3","Francia"],
        ["4","Italia"]
        ]';
        $field->save();

        // Date
        $field = new ModuleField();
        $field->order_list = 8;
        $field->order_create = 8;
        $field->crud_module_id = $module->id;
        $field->field_type_slug = 'date';
        $field->column_name = 'birthdate';
        $field->column_title = 'Fecha de nacimiento';
        $field->in_list = false;
        $field->in_create = true;
        $field->in_edit = true;
        $field->in_show = false;
        $field->is_required = true;
        $field->can_modify = true;
        $field->data = '';
        $field->save();

        // Datetime
        $field = new ModuleField();
        $field->order_list = 8;
        $field->order_create = 8;
        $field->crud_module_id = $module->id;
        $field->field_type_slug = 'datetime';
        $field->column_name = 'meeting_at';
        $field->column_title = 'Dia y hora de la reunión';
        $field->in_list = false;
        $field->in_create = true;
        $field->in_edit = true;
        $field->in_show = false;
        $field->is_required = true;
        $field->can_modify = true;
        $field->data = '';
        $field->save();

        // Time
        $field = new ModuleField();
        $field->order_list = 8;
        $field->order_create = 8;
        $field->crud_module_id = $module->id;
        $field->field_type_slug = 'time';
        $field->column_name = 'salida_at';
        $field->column_title = 'Hora de salida';
        $field->in_list = false;
        $field->in_create = true;
        $field->in_edit = true;
        $field->in_show = false;
        $field->is_required = true;
        $field->can_modify = true;
        $field->data = '';
        $field->save();


        // Color
        $field = new ModuleField();
        $field->order_list = 8;
        $field->order_create = 8;
        $field->crud_module_id = $module->id;
        $field->field_type_slug = 'color';
        $field->column_name = 'color';
        $field->column_title = 'Color';
        $field->in_list = false;
        $field->in_create = true;
        $field->in_edit = true;
        $field->in_show = false;
        $field->is_required = true;
        $field->can_modify = true;
        $field->data = '';
        $field->default_value = '#003BCE';
        $field->save();

        // Multi Check box
        $field = new ModuleField();
        $field->order_list = 8;
        $field->order_create = 8;
        $field->crud_module_id = $module->id;
        $field->field_type_slug = 'checkboxMulti';
        $field->column_name = 'multicheckbox';
        $field->column_title = 'Multi Check box';
        $field->in_list = false;
        $field->in_create = true;
        $field->in_edit = true;
        $field->in_show = false;
        $field->is_required = true;
        $field->can_modify = true;
        $field->data = '[["1","A"],["2","B"],["3","C"],["4","D"],["5","E"],["6","F"]]';
        $field->save();
    }

    public function tableOrdering(Request $request, $module_id)
    {
        // Si no tiene permisos para ver el listado lo echa.
        if (!auth()->user()->isAbleTo('admin-modulos-crud-list')) {
            app()->abort(403);
        }

        $page_title = trans("crud-generator::fields/admin_lang.table_order");

        $module = Module::find($module_id);
        if (empty($module)) {
            app()->abort(500);
        }

        $fields = ModuleField::select(
            array(
                'crud_module_fields.id',
                'column_name',
                'column_title',
                'crud_field_types.name',
                'in_list'
            )
        )
            ->join("crud_field_types", "crud_field_types.slug", "=", "crud_module_fields.field_type_slug")

            ->where('crud_module_id', $module->id)
            ->where('in_list', true)
            ->orderBy('order_list')
            ->get();

        $form_data = array(
            'route' => array('admin.crud-generator.fields.table', $module->id), 'method' => 'POST',
            'id' => 'formData', 'class' => 'form-horizontal'
        );

        return view(
            "crud-generator::fields.admin_table_order",
            compact(
                'page_title',
                'module',
                'fields',
                'form_data'
            )
        )->with('page_title_icon', $this->page_title_icon);
    }

    public function tableOrderingStore(Request $request, $module_id)
    {
        // Si no tiene permisos para ver el listado lo echa.
        if (!auth()->user()->isAbleTo('admin-modulos-crud-list')) {
            app()->abort(403);
        }

        $module = Module::find($module_id);
        if (empty($module)) {
            app()->abort(500);
        }

        $field_ids = $request->get('row_id', []);

        $count = 1;
        foreach ($field_ids as $field_id) {
            $field = ModuleField::find($field_id);
            if (empty($field)) {
                app()->abort(500);
            }

            $field->order_list = $count++;
            $field->save();
        }

        return redirect()->to('admin/crud-generator/' . $module->id . '/fields/table')
            ->with('success', trans('crud-generator::fields/admin_lang.save_ok'));
    }

    public function gridOrdering(Request $request, $module_id)
    {
        // Si no tiene permisos para ver el listado lo echa.
        if (!auth()->user()->isAbleTo('admin-modulos-crud-list')) {
            app()->abort(403);
        }

        $page_title = trans("crud-generator::fields/admin_lang.grid_order");

        $module = Module::find($module_id);
        if (empty($module)) {
            app()->abort(500);
        }

        $fields = ModuleField::where('crud_module_id', $module->id)
            ->inCreate()
            ->whereNotIn(
                'id',
                DB::table('crud_module_grid')
                    ->where('crud_module_id', $module->id)
                    ->whereNotNull('crud_module_field_id')
                    ->pluck('crud_module_field_id')
            )
            ->orderBy('order_create')
            ->get();


        $grid = ModuleGrid::where('crud_module_id', $module->id)
            ->orderBy('row', 'ASC')
            ->orderBy('col', 'ASC')
            ->get();

        $form_data = array(
            'route' => array('admin.crud-generator.fields.grid', $module->id), 'method' => 'POST',
            'id' => 'formData', 'class' => 'form-horizontal'
        );

        return view(
            "crud-generator::fields.admin_grid_order",
            compact(
                'page_title',
                'module',
                'fields',
                'form_data',
                'grid'
            )
        )->with('page_title_icon', $this->page_title_icon);
    }

    public function gridOrderingStore(Request $request, $module_id)
    {
        // Si no tiene permisos para ver el listado lo echa.
        if (!auth()->user()->isAbleTo('admin-modulos-crud-list')) {
            app()->abort(403);
        }

        $module = Module::find($module_id);
        if (empty($module)) {
            app()->abort(500);
        }

        $field_ids = $request->get('item', []);
        $rows = $request->get('row', []);

        // Grabamos la estructura. Hay que tener en cuenta el orden de llegada porque
        // puede haber una reordenación de las filas en navegador.
        // Haremos un mapeo de orden e id para depues sustituirlo en los campos
        ModuleGrid::where('crud_module_id', $module->id)->delete();

        $reordered_rows = [];
        $row_count = 1;
        foreach ($rows as $row) {
            $row_id = $row['id'];
            $reordered_rows[$row_count] = $row_id;
            foreach ($row['columns'] as $column) {
                $position = new ModuleGrid();
                $position->crud_module_id = $module->id;
                $position->crud_module_field_id = null;

                $position->row = $row_count;
                $position->col = $column;
                $position->save();
            }

            $row_count++;
        }

        // Ahora las filas y columnas de la rejilla
        $count = 1;
        foreach ($field_ids as $field_id) {
            $field = ModuleField::find($field_id['id']);
            if (empty($field)) {
                app()->abort(500);
            }
            if ($field_id['row'] == 0) {
                $field->order_create = 0;
            } else {
                $field->order_create = $count++;

                // Buscamos la fila dentro de nuestro listado reordenado de filas para ver su posisión real final
                $row_id_final = array_search($field_id['row'], $reordered_rows);
                $position = ModuleGrid::where('crud_module_id', $module->id)
                    ->where('row', $row_id_final)
                    ->where('col', $field_id['column'])
                    ->first();
                if (!empty($position)) {
                    $position->crud_module_field_id = $field->id;

                    $position->save();
                }
            }

            $field->save();
        }

        // Ahora finalmente actualizamos el max col para facilitar la presentacion
        DB::statement(
            DB::raw("
                        UPDATE crud_module_grid g
                        INNER JOIN  (
                        SELECT crud_module_id, `row` AS fila, COUNT(*) AS q
                        FROM crud_module_grid g
                        WHERE crud_module_id=?
                        GROUP BY crud_module_id, `row`) AS x ON x.crud_module_id = g.crud_module_id AND x.fila=g.`row`
                        SET g.max_col = x.q
                        "),
            array($module->id)
        );

        return redirect()->to('admin/crud-generator/' . $module->id . '/fields/grid')
            ->with('success', trans('crud-generator::fields/admin_lang.save_ok'));
    }
}
