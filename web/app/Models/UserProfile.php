<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\UserProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = "user_profiles";

    /**
     * Create a new factory instance for the model.
     *
     * @return \\Illuminate\\Database\\Eloquent\\Factories\\Factory
     */
    protected static function newFactory()
    {
        return new UserProfileFactory();
    }


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }


    public function scopeUsers($query)
    {
        return $query->join('users', 'users.id', '=', 'user_profiles.user_id');
    }


    public function getFullNameAttribute()
    {
        return trim($this->attributes['first_name'] . " " . $this->attributes['last_name']);
    }

    public function getBirthdateFormattedAttribute()
    {
        if (!empty($this->birthdate)) {
            return (Carbon::createFromFormat('Y-m-d', $this->birthdate))->format('d/m/Y');
        }

        return '';
    }
    public function centro()
    {
        return $this->hasOne('App\Modules\Centers\Models\Center', 'id', 'center_id');
    }

}
