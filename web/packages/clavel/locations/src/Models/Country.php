<?php

namespace Clavel\Locations\Models;

use Carbon\Carbon;
use Database\Factories\CountryFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    use \Astrotomic\Translatable\Translatable;

    use SoftDeletes;
    public $translatedAttributes = [
        'name',
        'short_name',
        'locale'
    ];
    public $useTranslationFallback = true;

    protected $table = "countries";

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'created_at',
        'updated_at',
        'active',
        'alpha2_code',
        'alpha3_code',
        'numeric_code'
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \\Illuminate\\Database\\Eloquent\\Factories\\Factory
     */
    protected static function newFactory()
    {
        return new CountryFactory();
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
