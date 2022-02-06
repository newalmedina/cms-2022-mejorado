<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FrontRegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'user_profile.first_name.required' => trans('users/lang.nombre_obligatorio'),
            'user_profile.last_name.required' => trans('users/lang.apellidos_obligatorio'),
            'nif.required' => trans('profile/front_lang.nif_obligatorio'),
            'email.required' => trans('users/lang.email_obligatorio'),
            'email.email' => trans('users/lang.email_formato_incorrecto'),
            'email.unique' => trans('users/lang.email_ya_existe'),
            // 'username.required' => trans('users/lang.required_username'),
            // 'username.unique' => trans('users/lang.usuarios_ya_existe'),
            'password.required' => trans('users/lang.required_password'),
            'password.confirmed' => trans('users/lang.password_no_coincide'),
            'password.min' => trans('users/lang.password_min'),
            'phone.required' => trans('profile/front_lang.phone_obligatorio'),
            'phone.numeric' => trans('profile/front_lang.phone_formato_incorrecto'),
            'phone.digits_between' => trans('profile/front_lang.phone_formato_incorrecto'),
            'user_profile.confirmed.required' => trans('profile/front_lang.user_profile_confirmed'),

        ];
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = array(
            'user_profile.first_name' => 'required',
            'user_profile.last_name' => 'required',
            'nif' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'phone' => 'nullable|numeric|digits_between:9,15',
            // 'username' => 'unique:users,username|required',
            'user_profile.confirmed' => 'required',
        );

        return $rules;
    }
}
