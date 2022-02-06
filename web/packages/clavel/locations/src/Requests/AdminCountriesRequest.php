<?php

namespace Clavel\Locations\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminCountriesRequest extends FormRequest
{
    protected $validationRules = array();
    protected $locale;

    public function __construct()
    {
        parent::__construct();

        $this->locale = app()->getLocale();
        $this->validationRules['active'] = 'required';
        $this->validationRules['lang.'.$this->locale.'.name'] = 'required';
        $this->validationRules['alpha2_code'] = 'required';
        $this->validationRules['alpha3_code'] = 'required';
        $this->validationRules['numeric_code'] = 'integer|required|min:1|max:9000';
    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Si no tiene permisos para ver el listado lo echa.
        if (!auth()->user()->isAbleTo('admin-countries-create') || !auth()->user()->isAbleTo('admin-countries-update')) {
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
            'active.required' => trans('locations::countries/admin_lang.fields.active_required'),
        'lang.'.$this->locale.'.name.required' => trans('locations::countries/admin_lang.fields.name_required'),
        'alpha2_code.required' => trans('locations::countries/admin_lang.fields.alpha2_code_required'),
        'alpha3_code.required' => trans('locations::countries/admin_lang.fields.alpha3_code_required'),
        'numeric_code.integer' => trans('locations::countries/admin_lang.fields.numeric_code_integer'),
        'numeric_code.required' => trans('locations::countries/admin_lang.fields.numeric_code_required'),
        'numeric_code.min' => trans('locations::countries/admin_lang.fields.numeric_code_min'),
        'numeric_code.max' => trans('locations::countries/admin_lang.fields.numeric_code_max')
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
