<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Arr;
use App\Models\PasswordHistoryRepo;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        PasswordHistoryRepo::storeCurrentPasswordInHistory($user->password, $user->id);
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        if ($password = Arr::get($user->getChanges(), 'password')) {
            PasswordHistoryRepo::storeCurrentPasswordInHistory($password, $user->id);
        }
    }
}
