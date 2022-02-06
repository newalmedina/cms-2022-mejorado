<?php

namespace App\Models;

use Database\Factories\IdiomaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Idioma extends Model
{
    // Traits
    use HasFactory;
    use \Astrotomic\Translatable\Translatable;

    protected $table = "idiomas";

    public $translatedAttributes = ['name', 'locale'];
    public $useTranslationFallback = true;

    protected $fillable = ['code', 'name', 'active', 'default'];

    protected $dates = ['created_at',
        'updated_at',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new IdiomaFactory();
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeDefault($query)
    {
        return $query->where('default', 1);
    }

    public function getLocaleNameAttribute()
    {
        $translator = $this->translate(App::getLocale());
        if (empty($translator)) {
            return ("");
        }
        return $translator->name;
    }
}
