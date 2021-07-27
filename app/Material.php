<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use SoftDeletes;

    protected $appends = ['full_description'];

    protected $fillable = [
        'code',
        'description',
        'measure',
        'unit_measure_id',
        'stock_max',
        'stock_min',
        'stock_current',
        'priority',
        'unit_price',
        'image',
        'category_id',
        'subcategory_id',
        'material_type_id',
        'subtype_id',
        'brand_id',
        'exampler_id',
        'warrant_id',
        'quality_id',
        'typescrap_id'
    ];

    public function getFullDescriptionAttribute()
    {
        $subcategory = ( is_null($this->subcategory) ) ? '': ' '.$this->subcategory->name;
        $type = ( is_null($this->materialType) ) ? '': ' '.$this->materialType->name;
        $subtype = ( is_null($this->subType) ) ? '': ' '.$this->subType->name;
        $warrant = ( is_null($this->warrant) ) ? '': ' '.$this->warrant->name;
        $quality = ( is_null($this->quality) ) ? '': ' '.$this->quality->name;

        return "{$this->description}". $subcategory . $type . $subtype . $warrant . $quality . " {$this->measure}";
    }

    public function unitMeasure()
    {
        return $this->belongsTo('App\UnitMeasure');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function subcategory()
    {
        return $this->belongsTo('App\Subcategory');
    }

    public function materialType()
    {
        return $this->belongsTo('App\MaterialType', 'material_type_id');
    }

    public function subType()
    {
        return $this->belongsTo('App\Subtype', 'subtype_id');
    }

    public function exampler()
    {
        return $this->belongsTo('App\Exampler');
    }

    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }

    public function warrant()
    {
        return $this->belongsTo('App\Warrant');
    }

    public function quality()
    {
        return $this->belongsTo('App\Quality');
    }

    public function typeScrap()
    {
        return $this->belongsTo('App\Typescrap', 'typescrap_id');
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
