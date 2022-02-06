<?php

namespace App\Modules\Products\Models;

use App\Modules\Categories\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Product extends Model
{

    const PAD_LEFT = 5;
    use SoftDeletes;

    protected $table = "products";

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
        'code',
        'price',
        'taxes',
        'real_price',
        'has_taxes',
        'category_id',
    ];



    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function makeCode()
    {
        if ($this->numeric_code == '') {
            $model_last_code = DB::table("products")->orderBy('id', 'DESC')->first();
            $model_last_code = empty($model_last_code) ? 1 : $model_last_code->id + 1;
            $this->code =  config('makeCode.product_prefix') . str_pad($model_last_code, self::PAD_LEFT, '0', STR_PAD_LEFT);
            $this->save();
        }
    }
}
