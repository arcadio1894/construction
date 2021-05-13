<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'description',
        'measure',
        'unit_measure',
        'stock_max',
        'stock_min',
        'stock_current',
        'priority',
        'unit_price',
        'image',
        'material_type_id',
        'category_id'
    ];

    public function materialType()
    {
        return $this->belongsTo('App\MaterialType');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    protected $dates = ['deleted_at'];
}
