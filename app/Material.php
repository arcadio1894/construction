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
        'category_id',
        'brand_id',
        'exampler_id'
    ];

    public function materialType()
    {
        return $this->belongsTo('App\MaterialType');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function exampler()
    {
        return $this->belongsTo('App\Exampler');
    }

    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }

    public function equipments()
    {
        return $this->belongsToMany('App\Equipment', 'equipment_materials')
            ->withPivot('equipment_id', 'quantity', 'unit_price', 'long', 'width', 'kilos', 'percentage', 'state', 'price', 'availability');
    }

    public function defaultItems()
    {
        return $this->hasMany('App\DefaultItem');
    }

    public function detailEntries()
    {
        return $this->hasMany('App\DetailEntry');
    }

    protected $dates = ['deleted_at'];
}
