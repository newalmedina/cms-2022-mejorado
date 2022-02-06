<?php

namespace App\Modules\Categories\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\CategoryFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Category extends Model
{

    const PAD_LEFT = 5;

    use SoftDeletes,
        HasFactory;

    protected $table = "categories";

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'created_at',
        'updated_at',
        'name',
        'description',
        'active',
    ];





    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new CategoryFactory;
    }

    public function makeCode()
    {
        if ($this->numeric_code == '') {
            $model_last_code = DB::table("categories")->orderBy('id', 'DESC')->first();
            $model_last_code = empty($model_last_code) ? 1 : $model_last_code->id + 1;
            $this->code =  config('psms.patient_prefix') . '-' . str_pad($model_last_code, self::PAD_LEFT, '0', STR_PAD_LEFT);
            $this->save();
        }
    }
}
