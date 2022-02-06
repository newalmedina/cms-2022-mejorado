<?php

namespace App\Traits;

use App\Models\PasswordHistory;

trait PasswordHistoryTrait
{
    /**
     * @return mixed
     */
    public function passwordHistory()
    {
        return $this->hasMany(PasswordHistory::class)
            ->latest();
    }

    public function deletePasswordHistory()
    {
        $keep = config('auth.password_keep');
        $ids = $this->passwordHistory()
            ->pluck('id')
            ->sort()
            ->reverse();

        if ($ids->count() < $keep) {
            return;
        }

        $delete = $ids->splice($keep);

        $this->passwordHistory()
            ->whereIn('id', $delete)
            ->delete();
    }
}
