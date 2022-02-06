<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FrontLoginRequest extends FormRequest
{
    protected $loginField;
    protected $loginValue;

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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->loginField = filter_var(
            $this->input('login'),
            FILTER_VALIDATE_EMAIL
        ) ? 'email' : 'username';
        $this->loginValue = $this->input('login');
        $this->merge([$this->loginField => $this->loginValue]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // No verificamos en base de datos para redirigir hacia la pantalla de /login
            //'email' => 'required_without:username|string|email|exists:users,email',
            'email' => 'required_without:username|string|email',
            //'username' => 'required_without:email|string|exists:users,username',
            'username' => 'required_without:email|string',
            'password' => 'required|string'
        ];
    }


    /**
     * Get the needed authorization credentials from the request.
     *
     * @return array
     */
    protected function credentials()
    {
        // return $this->only($this->loginField, 'password');
        return array_merge($this->only($this->loginField, 'password'), ['active' => 1], ['confirmed' => 1]);
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        if (!Auth::attempt($this->credentials(), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            // Podemos devolver una excepcion que nos retornara a la página de origen o forzar a la de login
            throw ValidationException::withMessages([
                'login' => __('auth.failed'),
            ])
            ->redirectTo('/login');
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('login')) . '|' . $this->ip();
    }
}
