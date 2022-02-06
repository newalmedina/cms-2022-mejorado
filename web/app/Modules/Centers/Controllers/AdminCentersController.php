<?php

namespace App\Modules\Centers\Controllers;

use App\Http\Controllers\AdminController;
use App\Models\Permission;
use App\Modules\Centers\Models\Center;
use Clavel\Locations\Models\Province;
use App\Helpers\Clavel\ExcelHelper;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


use App\Modules\Centers\Requests\AdminCentersRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;

class AdminCentersController extends AdminController
{
    protected $page_title_icon = '<i class="fa fas fa-hospital" aria-hidden="true"></i>';

    public function __construct()
    {
        parent::__construct();
        $this->access_permission = 'admin-centers';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Si no tiene permisos para ver el listado lo echa.
        if (!auth()->user()->isAbleTo('admin-centers-list')) {
            app()->abort(403);
        }

        $page_title = trans("Centers::centers/admin_lang.centers");

        return view("Centers::admin_index", compact('page_title'))
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
        if (!auth()->user()->isAbleTo('admin-centers-create')) {
            app()->abort(403);
        }

        $center = new Center();
        $form_data = array(
            'route' => array('centers.store'),
            'method' => 'POST',
            'id' => 'formData',
            'class' => 'form-horizontal'
        );
        $page_title = trans("Centers::centers/admin_lang.nueva_center");



        $provinces = Province::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view(
            'Centers::admin_edit',
            compact(
                'page_title',
                'center',
                'form_data',
                'provinces'
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
    public function store(AdminCentersRequest $request)
    {
        if (!auth()->user()->isAbleTo('admin-centers-create')) {
            app()->abort(403);
        }

        $center = new Center();
        if (!$this->saveCenter($request, $center)) {
            return redirect()->route('centers.create')
                ->with('error', trans('Centers::centers/admin_lang.save_ko'));
        }

        $saveReturn = $request->get('form_return', 0);
        if ($saveReturn == 1) {
            return redirect()->to('admin/centers/')
                ->with('success', trans('Centers::centers/admin_lang.save_ok'));
        }
        return redirect()->to('admin/centers/'.$center->id."/edit")
            ->with('success', trans('Centers::centers/admin_lang.save_ok'));
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
        if (!auth()->user()->isAbleTo('admin-centers-update')) {
            app()->abort(403);
        }

        $center = Center::find($id);
        if (empty($center)) {
            app()->abort(404);
        }

        $form_data = array(
            'route' => array('centers.update', $center->id),
            'method' => 'PATCH',
            'id' => 'formData',
            'class' => 'form-horizontal'
        );
        $page_title = trans("Centers::centers/admin_lang.editar_center");



        $provinces = Province::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view(
            'Centers::admin_edit',
            compact(
                'page_title',
                'center',
                'form_data',
                'provinces'
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
    public function update(AdminCentersRequest $request, $id)
    {
        if (!auth()->user()->isAbleTo('admin-centers-update')) {
            app()->abort(403);
        }

        $center = Center::find($id);
        if (empty($center)) {
            app()->abort(404);
        }

        if (!$this->saveCenter($request, $center)) {
            return redirect()->to('admin/centers/'.$center->id."/edit")
                ->with('error', trans('Centers::centers/admin_lang.save_ko'));
        }

        $saveReturn = $request->get('form_return', 0);

        if ($saveReturn == 1) {
            return redirect()->to('admin/centers/')
                ->with('success', trans('Centers::centers/admin_lang.save_ok'));
        }

        return redirect()->to('admin/centers/'.$center->id."/edit")
            ->with('success', trans('Centers::centers/admin_lang.save_ok'));
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
        if (!auth()->user()->isAbleTo('admin-centers-delete')) {
            app()->abort(403);
        }

        $center = Center::find($id);
        if (empty($center)) {
            app()->abort(404);
        }

        $center->delete();

        return response()->json(array(
            'success' => true,
            'msg' => trans("Centers::centers/admin_lang.deleted"),
            'id' => $center->id
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
        if (!auth()->user()->isAbleTo('admin-centers-delete')) {
            app()->abort(403);
        }

        $ids = explode(",", $request->get("ids", ""));

        foreach ($ids as $key => $value) {
            $center = Center::find($value);
            if (!empty($center)) {
                $center->delete();
            }
        }

        return response()->json(array(
            'success' => true,
            'msg' => trans("Centers::centers/admin_lang.deleted_records")
        ));
    }

    public function getData()
    {
        $query = DB::table('centers as c')
            ->select(
                array(
                    'c.id',
                'c.active',
                'c.name',
                'c.province_id',
                'c.phone',
                'c.email'
                )
            )
            ->whereNull('c.deleted_at')

            ;

        $table = Datatables::of($query);
        $table->editColumn('active', function ($data) {
            return '<button class="btn '.($data->active?"btn-success":"btn-danger").' btn-sm" '.
                    (auth()->user()->isAbleTo("admin-centers-update")?"onclick=\"javascript:changeStatus('".
                        url('admin/centers/state/'.$data->id)."');\"":"").'
                        data-content="'.($data->active?
                        trans('general/admin_lang.descativa'):
                        trans('general/admin_lang.activa')).'"
                        data-placement="right" data-toggle="popover">
                        <i class="fa '.($data->active?"fa-eye":"fa-eye-slash").'" aria-hidden="true"></i>
                        </button>';
        });


        $table->editColumn('check', function ($row) {
            return '<input type="checkbox" name="selected_id[]" value="' . $row->id . '">';
        });

        $table->editColumn('actions', function ($data) {
            $actions = '';
            if (auth()->user()->isAbleTo("admin-centers-update")) {
                $actions .= '<button class="btn btn-primary btn-sm" onclick="javascript:window.location=\'' .
                        url('admin/centers/' . $data->id . '/edit') . '\';" data-content="' .
                        trans('general/admin_lang.modificar') . '" data-placement="right" data-toggle="popover">
                        <i class="fas fa-edit"></i></button> ';
            }
            if (auth()->user()->isAbleTo("admin-centers-delete")) {
                $actions .= '<button class="btn btn-danger btn-sm" onclick="javascript:deleteElement(\''.
                        url('admin/centers/'.$data->id).'\');" data-content="'.
                        trans('general/admin_lang.borrar').'" data-placement="left" data-toggle="popover">
                        <i class="fa fa-trash" aria-hidden="true"></i></button>';
            }

            return $actions;
        });


        $table->removeColumn('id');
        $table->rawColumns(['check','active', 'actions']);
        return $table->make();
    }

    public function setChangeState($id)
    {
        // Si no tiene permisos para modificar lo echamos
        if (!auth()->user()->isAbleTo('admin-centers-update')) {
            app()->abort(403);
        }

        $center = Center::find($id);

        if (!empty($center)) {
            $center -> active = !$center -> active;
            return $center -> save() ? 1 : 0 ;
        }

        return 0;
    }

    private function saveCenter(Request $request, Center $center)
    {
        try {
            DB::beginTransaction();

            $center->address = $request->input("address", "");
            $center->cp = $request->input("cp", "");
            $center->city = $request->input("city", "");
            $center->contact = $request->input("contact", "");
            $center->active = $request->input("active", false);
            $center->name = $request->input("name", "");
            $center->province_id = $request->input("province_id", null);
            $center->phone = $request->input("phone", "");
            $center->email = $request->input("email", "");
            $center->save();





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
            ->setTitle(trans('Centers::centers/admin_lang.listado_data'))
            ->setSubject(trans('Centers::centers/admin_lang.listado_data'))
            ->setDescription(trans('Centers::centers/admin_lang.listado_data'))
            ->setKeywords(trans('Centers::centers/admin_lang.listado_data'))
            ->setCategory('Informes');

        // Activamos la primera pestaña
        $spreadsheet->setActiveSheetIndex(0);

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle(substr(trans('Centers::centers/admin_lang.listado_data'), 0, 30));

        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setPaperSize(PageSetup::PAPERSIZE_A4);

        $sheet->getPageSetup()->setFitToWidth(1);

        $sheet->getHeaderFooter()->setOddHeader(trans('Centers::centers/admin_lang.listado_data'));
        $sheet->getHeaderFooter()->setOddFooter('&L&B' .
            $spreadsheet->getProperties()->getTitle() . '&RPágina &P de &N');

        $row = 1;

        // Ponemos las cabeceras
        $cabeceras = array(
             trans('Centers::centers/admin_lang.fields.address'),
        trans('Centers::centers/admin_lang.fields.cp'),
        trans('Centers::centers/admin_lang.fields.city'),
        trans('Centers::centers/admin_lang.fields.contact'),
        trans('Centers::centers/admin_lang.fields.id'),
        trans('Centers::centers/admin_lang.fields.active'),
        trans('Centers::centers/admin_lang.fields.name'),
        trans('Centers::centers/admin_lang.fields.province'),
        trans('Centers::centers/admin_lang.fields.phone'),
        trans('Centers::centers/admin_lang.fields.email'),
        trans('Centers::centers/admin_lang.fields.created_at'),
        trans('Centers::centers/admin_lang.fields.updated_at')
        );

        $j=1;
        foreach ($cabeceras as $titulo) {
            $sheet->setCellValueByColumnAndRow($j++, $row, $titulo);
        }

        $columna_final = Coordinate::stringFromColumnIndex($j - 1);

        $sheet->getStyle('A'.$row.':'.$columna_final.$row)->getFont()->setBold(true);
        $sheet->getStyle('A'.$row.':'.$columna_final.$row)->getFont()->setSize(14);

        ExcelHelper::cellColor($sheet, 'A'.$row.':'.$columna_final.$row, 'ffc000');

        foreach (ExcelHelper::xrange('A', $columna_final) as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        $row++;

        // Ahora los registros
        $data = DB::table('centers')
        ->select(
            'centers.address',
            'centers.cp',
            'centers.city',
            'centers.contact',
            'centers.id',
            'centers.active',
            'centers.name',
            'centers.province_id',
            'centers.phone',
            'centers.email',
            'centers.created_at',
            'centers.updated_at'
        )
        ->orderBy('created_at', 'DESC')
        ->get();



        foreach ($data as $key => $value) {
            $valores = array(
               $value->address,
            $value->cp,
            $value->city,
            $value->contact,
            $value->id,
            $value->active,
            $value->name,
            $value->province_id,
            $value->phone,
            $value->email,
            $value->created_at,
            $value->updated_at
            );

            $j=1;
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
        $file_name = trans('Centers::centers/admin_lang.listado_data')."_".Carbon::now()->format('YmdHis');
        $outPath = storage_path("app/exports/");
        if (!file_exists($outPath)) {
            mkdir($outPath, 0777, true);
        }
        $writer->save($outPath.$file_name.'.xlsx');

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $file_name.'.xlsx' . '"');
        header('Cache-Control: max-age=0');


        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
