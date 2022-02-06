<?php

namespace Clavel\Locations\Controllers;

use App\Http\Controllers\AdminController;
use App\Models\Permission;
use Clavel\Locations\Models\City;
use Clavel\Locations\Models\Ccaa;
use Clavel\Locations\Models\Country;
use Clavel\Locations\Models\Province;
use App\Helpers\Clavel\ExcelHelper;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

use Clavel\Locations\Models\CityTranslation;
use App\Services\LanguageService;
use Clavel\Locations\Requests\AdminCitiesRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;

class AdminCitiesController extends AdminController
{
    protected $page_title_icon = '<i class="fa fas fa-map-marker-alt" aria-hidden="true"></i>';

    public function __construct()
    {
        parent::__construct();
        $this->access_permission = 'admin-cities';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Si no tiene permisos para ver el listado lo echa.
        if (!auth()->user()->isAbleTo('admin-cities-list')) {
            app()->abort(403);
        }

        $page_title = trans("locations::cities/admin_lang.cities");

        return view("locations::cities/admin_index", compact('page_title'))
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
        if (!auth()->user()->isAbleTo('admin-cities-create')) {
            app()->abort(403);
        }

        $city = new City();
        $form_data = array(
            'route' => array('cities.store'),
            'method' => 'POST',
            'id' => 'formData',
            'class' => 'form-horizontal'
        );
        $page_title = trans("locations::cities/admin_lang.nueva_city");

        // Idioma
        $serviceTranslation = new LanguageService(app()->getLocale());
        $a_trans = $serviceTranslation->getTranslations($city);

        $ccaas = Ccaa::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $countries = Country::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $provinces = Province::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view(
            'locations::cities/admin_edit',
            compact(
                'page_title',
                'city',
                'form_data',
                'a_trans',
                'ccaas',
                'countries',
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
    public function store(AdminCitiesRequest $request)
    {
        if (!auth()->user()->isAbleTo('admin-cities-create')) {
            app()->abort(403);
        }

        $city = new City();
        if (!$this->saveCity($request, $city)) {
            return redirect()->route('cities.create')
                ->with('error', trans('locations::cities/admin_lang.save_ko'));
        }

        $saveReturn = $request->get('form_return', 0);
        if ($saveReturn == 1) {
            return redirect()->to('admin/cities/')
                ->with('success', trans('locations::cities/admin_lang.save_ok'));
        }
        return redirect()->to('admin/cities/'.$city->id."/edit")
            ->with('success', trans('locations::cities/admin_lang.save_ok'));
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
        if (!auth()->user()->isAbleTo('admin-cities-update')) {
            app()->abort(403);
        }

        $city = City::find($id);
        if (empty($city)) {
            app()->abort(404);
        }

        $form_data = array(
            'route' => array('cities.update', $city->id),
            'method' => 'PATCH',
            'id' => 'formData',
            'class' => 'form-horizontal'
        );
        $page_title = trans("locations::cities/admin_lang.editar_city");

        // Idioma
        $serviceTranslation = new LanguageService(app()->getLocale());
        $a_trans = $serviceTranslation->getTranslations($city);

        $ccaas = Ccaa::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $countries = Country::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $provinces = Province::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view(
            'locations::cities/admin_edit',
            compact(
                'page_title',
                'city',
                'form_data',
                'a_trans',
                'ccaas',
                'countries',
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
    public function update(AdminCitiesRequest $request, $id)
    {
        if (!auth()->user()->isAbleTo('admin-cities-update')) {
            app()->abort(403);
        }

        $city = City::find($id);
        if (empty($city)) {
            app()->abort(404);
        }

        if (!$this->saveCity($request, $city)) {
            return redirect()->to('admin/cities/'.$city->id."/edit")
                ->with('error', trans('locations::cities/admin_lang.save_ko'));
        }

        $saveReturn = $request->get('form_return', 0);

        if ($saveReturn == 1) {
            return redirect()->to('admin/cities/')
                ->with('success', trans('locations::cities/admin_lang.save_ok'));
        }

        return redirect()->to('admin/cities/'.$city->id."/edit")
            ->with('success', trans('locations::cities/admin_lang.save_ok'));
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
        if (!auth()->user()->isAbleTo('admin-cities-delete')) {
            app()->abort(403);
        }

        $city = City::find($id);
        if (empty($city)) {
            app()->abort(404);
        }

        $city->delete();

        return response()->json(array(
            'success' => true,
            'msg' => trans("locations::cities/admin_lang.deleted"),
            'id' => $city->id
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
        if (!auth()->user()->isAbleTo('admin-cities-delete')) {
            app()->abort(403);
        }

        $ids = explode(",", $request->get("ids", ""));

        foreach ($ids as $key => $value) {
            $city = City::find($value);
            if (!empty($city)) {
                $city->delete();
            }
        }

        return response()->json(array(
            'success' => true,
            'msg' => trans("locations::cities/admin_lang.deleted_records")
        ));
    }

    public function getData()
    {
        $locale = app()->getLocale();
        $query = DB::table('cities as c')
            ->join('city_translations as ct', function ($join) use ($locale) {
                $join->on('ct.city_id', '=', 'c.id');
                $join->on('ct.locale', '=', DB::raw("'".$locale."'"));
            })
            ->select(
                array(
                    'c.id',
                'c.active',
                'c.country_id',
                'c.province_id',
                'ct.name'
                )
            )
            ->whereNull('c.deleted_at')

            ;

        $table = Datatables::of($query);
        $table->editColumn('active', function ($data) {
            return '<button class="btn '.($data->active ? "btn-success" : "btn-danger").' btn-sm" '.
                    (auth()->user()->isAbleTo("admin-cities-update") ? "onclick=\"javascript:changeStatus('".
                        url('admin/cities/state/'.$data->id)."');\"" : "").'
                        data-content="'.($data->active ?
                        trans('general/admin_lang.descativa') :
                        trans('general/admin_lang.activa')).'"
                        data-placement="right" data-toggle="popover">
                        <i class="fa '.($data->active ? "fa-eye" : "fa-eye-slash").'" aria-hidden="true"></i>
                        </button>';
        });


        $table->editColumn('check', function ($row) {
            return '<input type="checkbox" name="selected_id[]" value="' . $row->id . '">';
        });

        $table->editColumn('actions', function ($data) {
            $actions = '';
            if (auth()->user()->isAbleTo("admin-cities-update")) {
                $actions .= '<button class="btn btn-primary btn-sm" onclick="javascript:window.location=\'' .
                        url('admin/cities/' . $data->id . '/edit') . '\';" data-content="' .
                        trans('general/admin_lang.modificar') . '" data-placement="right" data-toggle="popover">
                        <i class="fas fa-edit"></i></button> ';
            }
            if (auth()->user()->isAbleTo("admin-cities-delete")) {
                $actions .= '<button class="btn btn-danger btn-sm" onclick="javascript:deleteElement(\''.
                        url('admin/cities/'.$data->id).'\');" data-content="'.
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
        if (!auth()->user()->isAbleTo('admin-cities-update')) {
            app()->abort(403);
        }

        $city = City::find($id);

        if (!empty($city)) {
            $city -> active = !$city -> active;
            return $city -> save() ? 1 : 0 ;
        }

        return 0;
    }

    private function saveCity(Request $request, City $city)
    {
        try {
            DB::beginTransaction();

            $city->ccaa_id = $request->input("ccaa_id", null);
            $city->active = $request->input("active", false);
            $city->country_id = $request->input("country_id", null);
            $city->province_id = $request->input("province_id", null);
            $city->save();



            foreach ($request->input('lang') as $key => $value) {
                $itemTrans = CityTranslation::findOrNew(empty($value["id"]) ? 0 : $value["id"]);

                $itemTrans->city_id = $city->id;
                $itemTrans->locale = $key;
                $itemTrans->name = empty($value["name"]) ? "" : $value["name"];
                $itemTrans->save();
            }


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
            ->setTitle(trans('locations::cities/admin_lang.listado_data'))
            ->setSubject(trans('locations::cities/admin_lang.listado_data'))
            ->setDescription(trans('locations::cities/admin_lang.listado_data'))
            ->setKeywords(trans('locations::cities/admin_lang.listado_data'))
            ->setCategory('Informes');

        // Activamos la primera pestaña
        $spreadsheet->setActiveSheetIndex(0);

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle(substr(trans('locations::cities/admin_lang.listado_data'), 0, 30));

        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setPaperSize(PageSetup::PAPERSIZE_A4);

        $sheet->getPageSetup()->setFitToWidth(1);

        $sheet->getHeaderFooter()->setOddHeader(trans('locations::cities/admin_lang.listado_data'));
        $sheet->getHeaderFooter()->setOddFooter('&L&B' .
            $spreadsheet->getProperties()->getTitle() . '&RPágina &P de &N');

        $row = 1;

        // Ponemos las cabeceras
        $cabeceras = array(
             trans('locations::cities/admin_lang.fields.ccaa'),
        trans('locations::cities/admin_lang.fields.id'),
        trans('locations::cities/admin_lang.fields.active'),
        trans('locations::cities/admin_lang.fields.country'),
        trans('locations::cities/admin_lang.fields.province'),
        trans('locations::cities/admin_lang.fields.name'),
        trans('locations::cities/admin_lang.fields.created_at'),
        trans('locations::cities/admin_lang.fields.updated_at')
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
        $locale = app()->getLocale();
        $data = DB::table('cities')
        ->join('city_translations', function ($join) use ($locale) {
            $join->on('city_translations.city_id', '=', 'cities.id');
            $join->on('city_translations.locale', '=', DB::raw("'".$locale."'"));
        })
        ->select(
            array(
            'cities.ccaa_id',
            'cities.id',
            'cities.active',
            'cities.country_id',
            'cities.province_id',
            'city_translations.name',
            'cities.created_at',
            'cities.updated_at'
            )
        )
        ->whereNull('cities.deleted_at')
        ->orderBy('cities.created_at', 'DESC')
        ->get();



        foreach ($data as $key => $value) {
            $valores = array(
               $value->ccaa_id,
            $value->id,
            $value->active,
            $value->country_id,
            $value->province_id,
            $value->name,
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
        $file_name = trans('locations::cities/admin_lang.listado_data')."_".Carbon::now()->format('YmdHis');
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
