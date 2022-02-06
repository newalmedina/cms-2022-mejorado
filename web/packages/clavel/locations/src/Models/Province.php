<?php

namespace Clavel\Locations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\ProvinceFactory;
use Carbon\Carbon;

class Province extends Model
{
    use \Astrotomic\Translatable\Translatable;

    use SoftDeletes;

    use HasFactory;
    public $translatedAttributes = [
        'name',
        'locale'
    ];
    public $useTranslationFallback = true;

    protected $table = "provinces";

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'created_at',
        'updated_at',
        'active',
        'country_id',
        'province_id',
        'ccaa_id',
    ];



    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function ccaa()
    {
        return $this->belongsTo(Ccaa::class, 'ccaa_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new ProvinceFactory();
    }
}
