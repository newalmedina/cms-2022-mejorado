<?php

namespace App\Modules\Centers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\CenterFactory;
use Carbon\Carbon;

class Center extends Model
{
    use SoftDeletes,
        HasFactory;

    protected $table = "centers";

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'created_at',
        'updated_at',
        'name',
        'active',
        'address',
        'cp',
        'city',
        'province_id',
        'phone',
        'email',
        'contact',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new CenterFactory;
    }


    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
