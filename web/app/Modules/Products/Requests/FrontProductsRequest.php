<?php

namespace App\Modules\Products\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FrontProductsRequest extends FormRequest
{
    protected $validationRules = array();
    protected $locale;

    public function __construct()
    {
        parent::__construct();


        //$this->validationRules['description'] = 'required';
        $this->validationRules['price'] = 'required|numeric';
        $this->validationRules['amount'] = 'required|integer';
        $this->validationRules['category_id'] = 'required';
        $this->validationRules['active'] = 'required';
        $this->validationRules['has_taxes'] = 'required';
        $this->validationRules['taxes'] = 'numeric|nullable|required_if:has_taxes,1';
        $this->validationRules['name'] = 'required';
    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Si no tiene permisos para ver el listado lo echa.
        if (!auth()->user()->isAbleTo('front-products-create') || !auth()->user()->isAbleTo('front-products-update')) {
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
            'description.required' => trans('Products::products/front_lang.fields.description_required'),
            'price.required' => trans('Products::products/front_lang.fields.price_required'),
            'price.numeric' => trans('Products::products/front_lang.fields.price_numeric'),
            'category_id.required' => trans('Products::products/front_lang.fields.category_required'),
            'active.required' => trans('Products::products/front_lang.fields.active_required'),
            'has_taxes.required' => trans('Products::products/front_lang.fields.has_taxes_required'),
            'taxes.numeric' => trans('Products::products/front_lang.fields.taxes_numeric'),
            'taxes.required_if' => trans('Products::products/front_lang.fields.taxes_required_if'),
            'name.required' => trans('Products::products/front_lang.fields.name_required'),
            'amount.integer' => trans('Products::products/front_lang.fields.amount_integer')
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
