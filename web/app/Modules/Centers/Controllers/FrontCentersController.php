<?php

namespace App\Modules\Centers\Controllers;

use App\Http\Controllers\FrontController;
use App\Modules\Centers\Models\Center;
use App\Modules\Centers\Requests\CentersSelectorRequest;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class FrontCentersController extends FrontController
{
    public function listCenters()
    {
        $centros = Center::active()->pluck('name', 'id');
        $centros->prepend(trans("Centers::centers/front_lang.select_centro"), '');

        return view('Centers::front_select_center', compact('centros'));
    }

    public function postCenters(CentersSelectorRequest $request)
    {
        if (!auth()->user()->isAbleTo('frontend-centros-change')) {
            abort(404);
        }
        auth()->user()->userProfile->center_id = $request->center_id;
        auth()->user()->userProfile->save();
        return redirect('/');
    }
}
