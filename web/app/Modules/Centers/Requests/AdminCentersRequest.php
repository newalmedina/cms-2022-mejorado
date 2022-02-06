<?php

namespace App\Modules\Centers\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminCentersRequest extends FormRequest
{
    protected $validationRules = array();
    protected $locale;

    public function __construct()
    {
        parent::__construct();

        
        $this->validationRules['active'] = 'required';
        $this->validationRules['name'] = 'required';
        $this->validationRules['province_id'] = 'required';
    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Si no tiene permisos para ver el listado lo echa.
        if (!auth()->user()->isAbleTo('admin-centers-create') || !auth()->user()->isAbleTo('admin-centers-update')) {
            return false;
        }

        return true;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return array(
            'active.required' => trans('Centers::centers/admin_lang.fields.active_required'),
        'name.required' => trans('Centers::centers/admin_lang.fields.name_required'),
        'province_id.required' => trans('Centers::centers/admin_lang.fields.province_required')
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->validationRules;
    }
}
