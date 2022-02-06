<?php

namespace App\Rules;

use App\Models\PasswordHistoryRepo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Validation\Rule;

/*
 * Esta regla verifica si la contraseña introducida esta dentro del historico de contraseñas
 */
class NotFromPasswordHistory implements Rule
{
    protected $user;
    protected $checkPrevious;

    /**
     * NotFromPasswordHistory constructor.
     *
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->checkPrevious = config('auth.password_keep');
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $passwordHistories = PasswordHistoryRepo::fetchUser($this->user, $this->checkPrevious);
        foreach ($passwordHistories as $passwordHistory) {
            if (Hash::check($value, $passwordHistory->password)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('auth.password_history') == 'auth.password_history' ? 'The Password Has Been Used' : __('auth.password_history');
    }
}
