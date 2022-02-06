<?php

namespace Clavel\Locations\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminCitiesRequest extends FormRequest
{
    protected $validationRules = array();
    protected $locale;

    public function __construct()
    {
        parent::__construct();

        $this->locale = app()->getLocale();
        $this->validationRules['ccaa_id'] = 'required';
        $this->validationRules['active'] = 'required';
        $this->validationRules['country_id'] = 'required';
        $this->validationRules['province_id'] = 'required';
        $this->validationRules['lang.'.$this->locale.'.name'] = 'required';
    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Si no tiene permisos para ver el listado lo echa.
        if (!auth()->user()->isAbleTo('admin-cities-create') || !auth()->user()->isAbleTo('admin-cities-update')) {
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
            'ccaa_id.required' => trans('locations::cities/admin_lang.fields.ccaa_required'),
        'active.required' => trans('locations::cities/admin_lang.fields.active_required'),
        'country_id.required' => trans('locations::cities/admin_lang.fields.country_required'),
        'province_id.required' => trans('locations::cities/admin_lang.fields.province_required'),
        'lang.'.$this->locale.'.name.required' => trans('locations::cities/admin_lang.fields.name_required')
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
