<?php

namespace App\Modules\Categories\Controllers;

use App\Http\Controllers\FrontController;
use App\Models\Permission;
use App\Modules\Categories\Models\Category;
use App\Helpers\Clavel\ExcelHelper;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


use App\Modules\Categories\Requests\FrontCategoriesRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;

class FrontCategoriesController extends FrontController
{
    protected $page_title_icon = '<i class="fa fas fa-th-list" aria-hidden="true"></i>';

    public function __construct()
    {
        parent::__construct();
        $this->access_permission = 'front-categories';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Si no tiene permisos para ver el listado lo echa.
        if (!auth()->user()->isAbleTo('front-categories-list')) {
            app()->abort(403);
        }

        $page_title = trans("Categories::categories/front_lang.categories");

        return view("Categories::front_index", compact('page_title'))
            ->with('page_title_icon', $this->page_title_icon);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Si no tiene permisos para ver el listado lo echa.
        if (!auth()->user()->isAbleTo('front-categories-create')) {
            app()->abort(403);
        }

        $category = new Category();
        $form_data = array(
            'route' => array('categories.store'),
            'method' => 'POST',
            'id' => 'formData',
            'class' => 'form-horizontal'
        );
        $page_title = trans("Categories::categories/front_lang.nueva_category");





        return view(
            'Categories::front_edit',
            compact(
                'page_title',
                'category',
                'form_data'


            )
        )
            ->with('page_title_icon', $this->page_title_icon);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FrontCategoriesRequest $request)
    {
        if (!auth()->user()->isAbleTo('front-categories-create')) {
            app()->abort(403);
        }

        $category = new Category();
        if (!$this->saveCategory($request, $category)) {

            return redirect()->route('categories.create')
                ->with('error', trans('Categories::categories/front_lang.save_ko'));
        }

        $saveReturn = $request->get('form_return', 0);
        if ($saveReturn == 1) {
            return redirect()->to('front/categories/')
                ->with('success', trans('Categories::categories/front_lang.save_ok'));
        }
        return redirect()->to('front/categories/' . $category->id . "/edit")
            ->with('success', trans('Categories::categories/front_lang.save_ok'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Si no tiene permisos para ver el listado lo echa.
        if (!auth()->user()->isAbleTo('front-categories-update')) {
            app()->abort(403);
        }

        $category = Category::find($id);
        if (empty($category)) {
            app()->abort(404);
        }

        $form_data = array(
            'route' => array('categories.update', $category->id),
            'method' => 'PATCH',
            'id' => 'formData',
            'class' => 'form-horizontal'
        );
        $page_title = trans("Categories::categories/front_lang.editar_category");





        return view(
            'Categories::front_edit',
            compact(
                'page_title',
                'category',
                'form_data'


            )
        )
            ->with('page_title_icon', $this->page_title_icon);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FrontCategoriesRequest $request, $id)
    {
        if (!auth()->user()->isAbleTo('front-categories-update')) {
            app()->abort(403);
        }

        $category = Category::find($id);
        if (empty($category)) {
            app()->abort(404);
        }

        if (!$this->saveCategory($request, $category)) {
            return redirect()->to('front/categories/' . $category->id . "/edit")
                ->with('error', trans('Categories::categories/front_lang.save_ko'));
        }

        $saveReturn = $request->get('form_return', 0);

        if ($saveReturn == 1) {
            return redirect()->to('front/categories/')
                ->with('success', trans('Categories::categories/front_lang.save_ok'));
        }

        return redirect()->to('front/categories/' . $category->id . "/edit")
            ->with('success', trans('Categories::categories/front_lang.save_ok'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Si no tiene permisos para modificar lo echamos
        if (!auth()->user()->isAbleTo('front-categories-delete')) {
            app()->abort(403);
        }

        $category = Category::find($id);
        if (empty($category)) {
            app()->abort(404);
        }

        $category->delete();

        return response()->json(array(
            'success' => true,
            'msg' => trans("Categories::categories/front_lang.deleted"),
            'id' => $category->id
        ));
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroySelected(Request $request)
    {
        // Si no tiene permisos para modificar lo echamos
        if (!auth()->user()->isAbleTo('front-categories-delete')) {
            app()->abort(403);
        }

        $ids = explode(",", $request->get("ids", ""));

        foreach ($ids as $key => $value) {
            $category = Category::find($value);
            if (!empty($category)) {
                $category->delete();
            }
        }

        return response()->json(array(
            'success' => true,
            'msg' => trans("Categories::categories/front_lang.deleted_records")
        ));
    }

    public function getData()
    {
        $query = DB::table('categories as c')
            ->select(
                array(
                    'c.id',
                    'c.active',
                    'c.name'
                )
            )
            ->whereNull('c.deleted_at');

        $table = Datatables::of($query);
        $table->editColumn('active', function ($data) {
            return '<button class="btn ' . ($data->active ? "btn-success" : "btn-danger") . ' btn-sm" ' .
                (auth()->user()->isAbleTo("front-categories-update") ? "onclick=\"javascript:changeStatus('" .
                    url('front/categories/state/' . $data->id) . "');\"" : "") . '
                        data-content="' . ($data->active ?
                    trans('general/front_lang.descativa') :
                    trans('general/front_lang.activa')) . '"
                        data-placement="right" data-toggle="popover">
                        <i class="fa ' . ($data->active ? "fa-eye" : "fa-eye-slash") . '" aria-hidden="true"></i>
                        </button>';
        });


        $table->editColumn('check', function ($row) {
            return '<input type="checkbox" name="selected_id[]" value="' . $row->id . '">';
        });

        $table->editColumn('actions', function ($data) {
            $actions = '';
            if (auth()->user()->isAbleTo("front-categories-update")) {
                $actions .= '<button class="btn btn-primary btn-sm" onclick="javascript:window.location=\'' .
                    url('front/categories/' . $data->id . '/edit') . '\';" data-content="' .
                    trans('general/front_lang.modificar') . '" data-placement="right" data-toggle="popover">
                        <i class="fas fa-edit"></i></button> ';
            }
            if (auth()->user()->isAbleTo("front-categories-delete")) {
                $actions .= '<button class="btn btn-danger btn-sm" onclick="javascript:deleteElement(\'' .
                    url('front/categories/' . $data->id) . '\');" data-content="' .
                    trans('general/front_lang.borrar') . '" data-placement="left" data-toggle="popover">
                        <i class="fa fa-trash" aria-hidden="true"></i></button>';
            }

            return $actions;
        });


        $table->removeColumn('id');
        $table->rawColumns(['check', 'active', 'actions']);
        return $table->make();
    }

    public function setChangeState($id)
    {
        // Si no tiene permisos para modificar lo echamos
        if (!auth()->user()->isAbleTo('front-categories-update')) {
            app()->abort(403);
        }

        $category = Category::find($id);

        if (!empty($category)) {
            $category->active = !$category->active;
            return $category->save() ? 1 : 0;
        }

        return 0;
    }

    private function saveCategory(Request $request, Category $category)
    {
        try {
            DB::beginTransaction();

            $category->description = $request->input("description", "");
            $category->active = $request->input("active", false);
            $category->name = $request->input("name", "");
            $category->save();





            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
        return true;
    }

    public function generateExcel()
    {
        ini_set('memory_limit', '300M');

        if (ob_get_contents()) {
            ob_end_clean();
        }
        set_time_limit(1000);

        $spreadsheet = new Spreadsheet();
        $spreadsheet
            ->getProperties()
            ->setCreator(config('app.name', ''))
            ->setCompany(config('app.name', ''))
            ->setLastModifiedBy(config('app.name', '')) // última vez modificado por
            ->setTitle(trans('Categories::categories/front_lang.listado_data'))
            ->setSubject(trans('Categories::categories/front_lang.listado_data'))
            ->setDescription(trans('Categories::categories/front_lang.listado_data'))
            ->setKeywords(trans('Categories::categories/front_lang.listado_data'))
            ->setCategory('Informes');

        // Activamos la primera pestaña
        $spreadsheet->setActiveSheetIndex(0);

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle(substr(trans('Categories::categories/front_lang.listado_data'), 0, 30));

        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setPaperSize(PageSetup::PAPERSIZE_A4);

        $sheet->getPageSetup()->setFitToWidth(1);

        $sheet->getHeaderFooter()->setOddHeader(trans('Categories::categories/front_lang.listado_data'));
        $sheet->getHeaderFooter()->setOddFooter('&L&B' .
            $spreadsheet->getProperties()->getTitle() . '&RPágina &P de &N');

        $row = 1;

        // Ponemos las cabeceras
        $cabeceras = array(
            trans('Categories::categories/front_lang.fields.id'),
            trans('Categories::categories/front_lang.fields.active'),
            trans('Categories::categories/front_lang.fields.name'),
            trans('Categories::categories/front_lang.fields.description'),
            trans('Categories::categories/front_lang.fields.created_at'),
            trans('Categories::categories/front_lang.fields.updated_at')
        );

        $j = 1;
        foreach ($cabeceras as $titulo) {
            $sheet->setCellValueByColumnAndRow($j++, $row, $titulo);
        }

        $columna_final = Coordinate::stringFromColumnIndex($j - 1);

        $sheet->getStyle('A' . $row . ':' . $columna_final . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':' . $columna_final . $row)->getFont()->setSize(14);

        ExcelHelper::cellColor($sheet, 'A' . $row . ':' . $columna_final . $row, 'ffc000');

        foreach (ExcelHelper::xrange('A', $columna_final) as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        $row++;

        // Ahora los registros
        $data = DB::table('categories')
            ->select(
                'categories.id',
                'categories.active',
                'categories.name',
                'categories.description',
                'categories.created_at',
                'categories.updated_at'
            )
            ->orderBy('created_at', 'DESC')
            ->whereNull('deleted_at')
            ->get();



        foreach ($data as $key => $value) {

            $valores = array(
                $value->id,
                $value->active,
                $value->name,
                $value->description,
                $value->created_at,
                $value->updated_at
            );

            $j = 1;
            foreach ($valores as $valor) {
                $sheet->setCellValueByColumnAndRow($j++, $row, $valor);
            }
            $row++;
        }

        ExcelHelper::autoSizeCurrentRow($sheet);

        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setVerticalCentered(false);


        // Activamos la primera pestaña
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $file_name = trans('Categories::categories/front_lang.listado_data') . "_" . Carbon::now()->format('YmdHis');
        $outPath = storage_path("app/exports/");
        if (!file_exists($outPath)) {
            mkdir($outPath, 0777, true);
        }
        $writer->save($outPath . $file_name . '.xlsx');

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx' . '"');
        header('Cache-Control: max-age=0');


        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
