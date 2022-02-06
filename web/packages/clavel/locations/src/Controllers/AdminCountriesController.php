<?php

namespace Clavel\Locations\Controllers;

use App\Http\Controllers\AdminController;
use App\Models\Permission;
use Clavel\Locations\Models\Country;
use App\Helpers\Clavel\ExcelHelper;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

use Clavel\Locations\Models\CountryTranslation;
use App\Services\LanguageService;
use Clavel\Locations\Requests\AdminCountriesRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;

class AdminCountriesController extends AdminController
{
    protected $page_title_icon = '<i class="fa fas fa-globe-americas" aria-hidden="true"></i>';

    public function __construct()
    {
        parent::__construct();
        $this->access_permission = 'admin-countries';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Si no tiene permisos para ver el listado lo echa.
        if (!auth()->user()->isAbleTo('admin-countries-list')) {
            app()->abort(403);
        }

        $page_title = trans("locations::countries/admin_lang.countries");

        return view("locations::countries/admin_index", compact('page_title'))
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
        if (!auth()->user()->isAbleTo('admin-countries-create')) {
            app()->abort(403);
        }

        $country = new Country();
        $form_data = array(
            'route' => array('countries.store'),
            'method' => 'POST',
            'id' => 'formData',
            'class' => 'form-horizontal'
        );
        $page_title = trans("locations::countries/admin_lang.nueva_country");

        // Idioma
        $serviceTranslation = new LanguageService(app()->getLocale());
        $a_trans = $serviceTranslation->getTranslations($country);



        return view(
            'locations::countries/admin_edit',
            compact(
                'page_title',
                'country',
                'form_data',
                'a_trans'
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
    public function store(AdminCountriesRequest $request)
    {
        if (!auth()->user()->isAbleTo('admin-countries-create')) {
            app()->abort(403);
        }

        $country = new Country();
        if (!$this->saveCountry($request, $country)) {
            return redirect()->route('countries.create')
                ->with('error', trans('locations::countries/admin_lang.save_ko'));
        }

        $saveReturn = $request->get('form_return', 0);
        if ($saveReturn == 1) {
            return redirect()->to('admin/countries/')
                ->with('success', trans('locations::countries/admin_lang.save_ok'));
        }
        return redirect()->to('admin/countries/'.$country->id."/edit")
            ->with('success', trans('locations::countries/admin_lang.save_ok'));
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
        if (!auth()->user()->isAbleTo('admin-countries-update')) {
            app()->abort(403);
        }

        $country = Country::find($id);
        if (empty($country)) {
            app()->abort(404);
        }

        $form_data = array(
            'route' => array('countries.update', $country->id),
            'method' => 'PATCH',
            'id' => 'formData',
            'class' => 'form-horizontal'
        );
        $page_title = trans("locations::countries/admin_lang.editar_country");

        // Idioma
        $serviceTranslation = new LanguageService(app()->getLocale());
        $a_trans = $serviceTranslation->getTranslations($country);



        return view(
            'locations::countries/admin_edit',
            compact(
                'page_title',
                'country',
                'form_data',
                'a_trans'
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
    public function update(AdminCountriesRequest $request, $id)
    {
        if (!auth()->user()->isAbleTo('admin-countries-update')) {
            app()->abort(403);
        }

        $country = Country::find($id);
        if (empty($country)) {
            app()->abort(404);
        }

        if (!$this->saveCountry($request, $country)) {
            return redirect()->to('admin/countries/'.$country->id."/edit")
                ->with('error', trans('locations::countries/admin_lang.save_ko'));
        }

        $saveReturn = $request->get('form_return', 0);

        if ($saveReturn == 1) {
            return redirect()->to('admin/countries/')
                ->with('success', trans('locations::countries/admin_lang.save_ok'));
        }

        return redirect()->to('admin/countries/'.$country->id."/edit")
            ->with('success', trans('locations::countries/admin_lang.save_ok'));
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
        if (!auth()->user()->isAbleTo('admin-countries-delete')) {
            app()->abort(403);
        }

        $country = Country::find($id);
        if (empty($country)) {
            app()->abort(404);
        }

        $country->delete();

        return response()->json(array(
            'success' => true,
            'msg' => trans("locations::countries/admin_lang.deleted"),
            'id' => $country->id
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
        if (!auth()->user()->isAbleTo('admin-countries-delete')) {
            app()->abort(403);
        }

        $ids = explode(",", $request->get("ids", ""));

        foreach ($ids as $key => $value) {
            $country = Country::find($value);
            if (!empty($country)) {
                $country->delete();
            }
        }

        return response()->json(array(
            'success' => true,
            'msg' => trans("locations::countries/admin_lang.deleted_records")
        ));
    }

    public function getData()
    {
        $locale = app()->getLocale();
        $query = DB::table('countries as c')
            ->join('country_translations as ct', function ($join) use ($locale) {
                $join->on('ct.country_id', '=', 'c.id');
                $join->on('ct.locale', '=', DB::raw("'".$locale."'"));
            })
            ->select(
                array(
                    'c.id',
                'c.active',
                'ct.name',
                'c.alpha2_code',
                'c.alpha3_code',
                'c.numeric_code'
                )
            )
            ->whereNull('c.deleted_at')

            ;

        $table = Datatables::of($query);
        $table->editColumn('active', function ($data) {
            return '<button class="btn '.($data->active ? "btn-success" : "btn-danger").' btn-sm" '.
                    (auth()->user()->isAbleTo("admin-countries-update") ? "onclick=\"javascript:changeStatus('".
                        url('admin/countries/state/'.$data->id)."');\"" : "").'
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
            if (auth()->user()->isAbleTo("admin-countries-update")) {
                $actions .= '<button class="btn btn-primary btn-sm" onclick="javascript:window.location=\'' .
                        url('admin/countries/' . $data->id . '/edit') . '\';" data-content="' .
                        trans('general/admin_lang.modificar') . '" data-placement="right" data-toggle="popover">
                        <i class="fas fa-edit"></i></button> ';
            }
            if (auth()->user()->isAbleTo("admin-countries-delete")) {
                $actions .= '<button class="btn btn-danger btn-sm" onclick="javascript:deleteElement(\''.
                        url('admin/countries/'.$data->id).'\');" data-content="'.
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
        if (!auth()->user()->isAbleTo('admin-countries-update')) {
            app()->abort(403);
        }

        $country = Country::find($id);

        if (!empty($country)) {
            $country -> active = !$country -> active;
            return $country -> save() ? 1 : 0 ;
        }

        return 0;
    }

    private function saveCountry(Request $request, Country $country)
    {
        try {
            DB::beginTransaction();

            $country->active = $request->input("active", false);
            $country->alpha2_code = $request->input("alpha2_code", "");
            $country->alpha3_code = $request->input("alpha3_code", "");
            $country->numeric_code = $request->input("numeric_code", 0);
            $country->save();



            foreach ($request->input('lang') as $key => $value) {
                $itemTrans = CountryTranslation::findOrNew(empty($value["id"]) ? 0 : $value["id"]);

                $itemTrans->country_id = $country->id;
                $itemTrans->locale = $key;
                $itemTrans->short_name = empty($value["short_name"]) ? "" : $value["short_name"];
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
            ->setTitle(trans('locations::countries/admin_lang.listado_data'))
            ->setSubject(trans('locations::countries/admin_lang.listado_data'))
            ->setDescription(trans('locations::countries/admin_lang.listado_data'))
            ->setKeywords(trans('locations::countries/admin_lang.listado_data'))
            ->setCategory('Informes');

        // Activamos la primera pestaña
        $spreadsheet->setActiveSheetIndex(0);

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle(substr(trans('locations::countries/admin_lang.listado_data'), 0, 30));

        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setPaperSize(PageSetup::PAPERSIZE_A4);

        $sheet->getPageSetup()->setFitToWidth(1);

        $sheet->getHeaderFooter()->setOddHeader(trans('locations::countries/admin_lang.listado_data'));
        $sheet->getHeaderFooter()->setOddFooter('&L&B' .
            $spreadsheet->getProperties()->getTitle() . '&RPágina &P de &N');

        $row = 1;

        // Ponemos las cabeceras
        $cabeceras = array(
             trans('locations::countries/admin_lang.fields.short_name'),
        trans('locations::countries/admin_lang.fields.id'),
        trans('locations::countries/admin_lang.fields.active'),
        trans('locations::countries/admin_lang.fields.name'),
        trans('locations::countries/admin_lang.fields.alpha2_code'),
        trans('locations::countries/admin_lang.fields.alpha3_code'),
        trans('locations::countries/admin_lang.fields.numeric_code'),
        trans('locations::countries/admin_lang.fields.created_at'),
        trans('locations::countries/admin_lang.fields.updated_at')
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
        $data = DB::table('countries')
        ->join('country_translations', function ($join) use ($locale) {
            $join->on('country_translations.country_id', '=', 'countries.id');
            $join->on('country_translations.locale', '=', DB::raw("'".$locale."'"));
        })
        ->select(
            array(
            'country_translations.short_name',
            'countries.id',
            'countries.active',
            'country_translations.name',
            'countries.alpha2_code',
            'countries.alpha3_code',
            'countries.numeric_code',
            'countries.created_at',
            'countries.updated_at'
            )
        )
        ->whereNull('countries.deleted_at')
        ->orderBy('countries.created_at', 'DESC')
        ->get();



        foreach ($data as $key => $value) {
            $valores = array(
               $value->short_name,
            $value->id,
            $value->active,
            $value->name,
            $value->alpha2_code,
            $value->alpha3_code,
            $value->numeric_code,
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
        $file_name = trans('locations::countries/admin_lang.listado_data')."_".Carbon::now()->format('YmdHis');
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
