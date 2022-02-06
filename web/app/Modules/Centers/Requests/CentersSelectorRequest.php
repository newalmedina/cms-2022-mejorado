<?php

namespace App\Modules\Centers\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CentersSelectorRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->isAbleTo('frontend-centros-change');
    }

    public function rules()
    {
        return array(
            'center_id' => 'required'
        );
    }

    public function attributes()
    {
        return array(
            'center_id' => trans('Centers::centers/front_lang.nombre_centros')
        );
    }
}
