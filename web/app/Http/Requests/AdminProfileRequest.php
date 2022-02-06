<?php

namespace App\Http\Requests;

use App\Rules\NotFromPasswordHistory;
use Illuminate\Foundation\Http\FormRequest;

class AdminProfileRequest extends FormRequest
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
            'userProfile.first_name.required' => trans('profile/admin_lang._NOMBRE_USUARIO'),
            'userProfile.last_name.required' => trans('profile/admin_lang._APELLIDOS_USUARIO'),

            'email.required' => trans('profile/front_lang.email_obligatorio'),
            'email.email' => trans('profile/front_lang.email_formato_incorrecto'),
            'email.unique' => trans('profile/front_lang.email_ya_existe'),

            'username.unique' => 'El usuario ya existe. No puede seleccionarlo.',
            'username.required' => 'El nombre de usuario es obligatorio',

            'password.confirmed' => trans('users/lang.password_no_coincide'),
            'password.min' => trans('users/lang.password_min'),

        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user_id = auth()->user()->getAuthIdentifier();

        return [
            'userProfile.first_name' => 'required',
            'userProfile.last_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user_id,
            'username' => 'unique:users,username,'.$user_id.'|required',
            'password' => ['nullable', 'confirmed', 'min:6', new NotFromPasswordHistory($this->user())],
        ];
    }
}
