<?php

namespace App\Modules\Products\Controllers;

use App\Http\Controllers\FrontController;
use App\Models\Permission;
use App\Modules\Products\Models\Product;
use Clavel\Basic\Models\Models\Media;
use App\Helpers\Clavel\ExcelHelper;
use App\Modules\Categories\Models\Category;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


use App\Modules\Products\Requests\FrontProductsRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;

class FrontProductsController extends FrontController
{
    protected $page_title_icon = '<i class="fa fab fa-product-hunt" aria-hidden="true"></i>';

    public function __construct()
    {
        parent::__construct();
        $this->access_permission = 'front-products';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Si no tiene permisos para ver el listado lo echa.
        if (!auth()->user()->isAbleTo('front-products-list')) {
            app()->abort(403);
        }

        $page_title = trans("Products::products/front_lang.products");

        return view("Products::front_index", compact('page_title'))
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
        if (!auth()->user()->isAbleTo('front-products-create')) {
            app()->abort(403);
        }

        $product = new Product();
        $form_data = array(
            'route' => array('products.store'),
            'method' => 'POST',
            'id' => 'formData',
            'class' => 'form-horizontal'
        );
        $page_title = trans("Products::products/front_lang.nueva_product");



        $categories = Category::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view(
            'Products::front_edit',
            compact(
                'page_title',
                'product',
                'form_data',
                'categories'
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
    public function store(FrontProductsRequest $request)
    {
        if (!auth()->user()->isAbleTo('front-products-create')) {
            app()->abort(403);
        }

        $product = new Product();
        if (!$this->saveProduct($request, $product, "new")) {

            return redirect()->route('products.create')
                ->with('error', trans('Products::products/front_lang.save_ko'));
        }

        $saveReturn = $request->get('form_return', 0);
        if ($saveReturn == 1) {
            return redirect()->to('front/products/')
                ->with('success', trans('Products::products/front_lang.save_ok'));
        }
        return redirect()->to('front/products/' . $product->id . "/edit")
            ->with('success', trans('Products::products/front_lang.save_ok'));
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
        if (!auth()->user()->isAbleTo('front-products-update')) {
            app()->abort(403);
        }

        $product = Product::find($id);
        if (empty($product)) {
            app()->abort(404);
        }

        $form_data = array(
            'route' => array('products.update', $product->id),
            'method' => 'PATCH',
            'id' => 'formData',
            'class' => 'form-horizontal'
        );
        $page_title = trans("Products::products/front_lang.editar_product");



        $categories = Category::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view(
            'Products::front_edit',
            compact(
                'page_title',
                'product',
                'form_data',
                'categories'
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
    public function update(FrontProductsRequest $request, $id)
    {
        if (!auth()->user()->isAbleTo('front-products-update')) {
            app()->abort(403);
        }

        $product = Product::find($id);
        if (empty($product)) {
            app()->abort(404);
        }

        if (!$this->saveProduct($request, $product)) {
            return redirect()->to('front/products/' . $product->id . "/edit")
                ->with('error', trans('Products::products/front_lang.save_ko'));
        }

        $saveReturn = $request->get('form_return', 0);

        if ($saveReturn == 1) {
            return redirect()->to('front/products/')
                ->with('success', trans('Products::products/front_lang.save_ok'));
        }

        return redirect()->to('front/products/' . $product->id . "/edit")
            ->with('success', trans('Products::products/front_lang.save_ok'));
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
        if (!auth()->user()->isAbleTo('front-products-delete')) {
            app()->abort(403);
        }

        $product = Product::find($id);
        if (empty($product)) {
            app()->abort(404);
        }

        $product->delete();

        return response()->json(array(
            'success' => true,
            'msg' => trans("Products::products/front_lang.deleted"),
            'id' => $product->id
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
        if (!auth()->user()->isAbleTo('front-products-delete')) {
            app()->abort(403);
        }

        $ids = explode(",", $request->get("ids", ""));

        foreach ($ids as $key => $value) {
            $product = Product::find($value);
            if (!empty($product)) {
                $product->delete();
            }
        }

        return response()->json(array(
            'success' => true,
            'msg' => trans("Products::products/front_lang.deleted_records")
        ));
    }

    public function getData()
    {
        $query = DB::table('products as c')
            ->select(
                array(
                    'c.id',
                    'c.code',
                    'c.price',
                    'c.taxes',
                    'c.real_price',
                    'c.category_id',
                    'c.active',
                    'c.has_taxes',
                    'c.name'
                )
            )
            ->whereNull('c.deleted_at');

        $table = Datatables::of($query);
        $table->editColumn('active', function ($data) {
            return '<button class="btn ' . ($data->active ? "btn-success" : "btn-danger") . ' btn-sm" ' .
                (auth()->user()->isAbleTo("front-products-update") ? "onclick=\"javascript:changeStatus('" .
                    url('front/products/state/' . $data->id) . "');\"" : "") . '
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
            if (auth()->user()->isAbleTo("front-products-update")) {
                $actions .= '<button class="btn btn-primary btn-sm" onclick="javascript:window.location=\'' .
                    url('front/products/' . $data->id . '/edit') . '\';" data-content="' .
                    trans('general/front_lang.modificar') . '" data-placement="right" data-toggle="popover">
                        <i class="fas fa-edit"></i></button> ';
            }
            if (auth()->user()->isAbleTo("front-products-delete")) {
                $actions .= '<button class="btn btn-danger btn-sm" onclick="javascript:deleteElement(\'' .
                    url('front/products/' . $data->id) . '\');" data-content="' .
                    trans('general/front_lang.borrar') . '" data-placement="left" data-toggle="popover">
                        <i class="fa fa-trash" aria-hidden="true"></i></button>';
            }

            return $actions;
        });


        /* $table->editColumn('has_taxes', function ($row) {
            return $row->has_taxes == 1 ? trans('general/admin_lang.yes') : trans('general/admin_lang.no');
        });*/

        $table->removeColumn('id');
        $table->rawColumns(['check', 'active', 'actions']);
        return $table->make();
    }

    public function setChangeState($id)
    {
        // Si no tiene permisos para modificar lo echamos
        if (!auth()->user()->isAbleTo('front-products-update')) {
            app()->abort(403);
        }

        $product = Product::find($id);

        if (!empty($product)) {
            $product->active = !$product->active;
            return $product->save() ? 1 : 0;
        }

        return 0;
    }

    private function saveProduct(Request $request, Product $product, $action = 'update')
    {
        try {
            DB::beginTransaction();

            $product->description = $request->input("description", "");
            $product->price = $request->input("price", 0.0);
            $product->taxes = $request->input("taxes", 0.0);
            $product->amount = $request->input("amount", 0.0);
            $product->real_price = (($product->price *  $product->taxes) / 100) + $product->price;
            //  $product->real_price = $request->input("real_price", 0.0);
            $product->category_id = $request->input("category_id", null);
            $product->active = $request->input("active", false);
            $product->has_taxes = $request->input("has_taxes", false);
            $product->name = $request->input("name", "");
            if ($action == "new") {
                $product->makeCode();
            }
            $product->save();

            DB::commit();
        } catch (\Exception $e) {
            dd($e);
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
            ->setTitle(trans('Products::products/front_lang.listado_data'))
            ->setSubject(trans('Products::products/front_lang.listado_data'))
            ->setDescription(trans('Products::products/front_lang.listado_data'))
            ->setKeywords(trans('Products::products/front_lang.listado_data'))
            ->setCategory('Informes');

        // Activamos la primera pestaña
        $spreadsheet->setActiveSheetIndex(0);

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle(substr(trans('Products::products/front_lang.listado_data'), 0, 30));

        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setPaperSize(PageSetup::PAPERSIZE_A4);

        $sheet->getPageSetup()->setFitToWidth(1);

        $sheet->getHeaderFooter()->setOddHeader(trans('Products::products/front_lang.listado_data'));
        $sheet->getHeaderFooter()->setOddFooter('&L&B' .
            $spreadsheet->getProperties()->getTitle() . '&RPágina &P de &N');

        $row = 1;

        // Ponemos las cabeceras
        $cabeceras = array(
            trans('Products::products/admin_lang.fields.id'),
            trans('Products::products/admin_lang.fields.description'),
            trans('Products::products/admin_lang.fields.code'),
            trans('Products::products/admin_lang.fields.price'),
            trans('Products::products/admin_lang.fields.taxes'),
            trans('Products::products/admin_lang.fields.real_price'),
            trans('Products::products/admin_lang.fields.category'),
            trans('Products::products/admin_lang.fields.active'),
            trans('Products::products/admin_lang.fields.has_taxes'),
            trans('Products::products/admin_lang.fields.name'),
            trans('Products::products/admin_lang.fields.created_at'),
            trans('Products::products/admin_lang.fields.updated_at')
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
        $data = DB::table('products')
            ->select(
                'products.id',
                'products.description',
                'products.code',
                'products.price',
                'products.taxes',
                'products.real_price',
                'products.category_id',
                'products.active',
                'products.has_taxes',
                'products.name',
                'products.created_at',
                'products.updated_at'
            )
            ->orderBy('created_at', 'DESC')
            ->get();



        foreach ($data as $key => $value) {

            $valores = array(
                $value->id,
                $value->description,
                $value->code,
                $value->price,
                $value->taxes,
                $value->real_price,
                $value->category_id,
                $value->active,
                $value->has_taxes,
                $value->name,
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
        $file_name = trans('Products::products/front_lang.listado_data') . "_" . Carbon::now()->format('YmdHis');
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
