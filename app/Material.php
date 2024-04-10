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
        'typescrap_id',
        'enable_status',
        'full_name',
        'inventory'
    ];

    public function setNameProductAttribute($value)
    {
        $this->attributes['name_product'] = strtoupper($value);
    }

    public function scopeWhereConsumable($query, $column, $value)
    {
        return $query->where($column, 'like', $value.'%');
    }

    public function getQuantityItemsAttribute()
    {
        $items = Item::where('material_id', $this->id)
            ->where('usage', '<>', 'finished')
            ->get();
        $quantity = 0;
        if ( isset($items) )
        {
            $quantity = count($items);
        }
        return $quantity;
    }

    public function getFullDescriptionAttribute()
    {
        $description='';
        $subcategory = ( is_null($this->subcategory) ) ? '': ' '.$this->subcategory->name;
        $type = ( is_null($this->materialType) ) ? '': ' '.$this->materialType->name;
        $subtype = ( is_null($this->subType) ) ? '': ' '.$this->subType->name;
        $warrant = ( is_null($this->warrant) ) ? '': ' '.$this->warrant->name;
        $quality = ( is_null($this->quality) ) ? '': ' '.$this->quality->name;

        if($this->category_id == 2)
        {
            $pos = strripos($this->description, "(*) ");
            if ( $pos !== false ) {
                $description = $description . substr($this->description, 4);
            } else {
                $description = $description.$this->description;
            }
        } else {
            $description = $description.$this->description;
        }

        if (isset( $this->subcategory ))
        {
            if( ($this->category_id == 2 && trim($this->subcategory->name) == 'MIXTO') || $this->category_id == 8 )
            {
                $subcategory = '';
            }
        }

        $nombre_v1 = $description . $subcategory . $type . $subtype . $warrant . $quality . " {$this->measure}";

        $nombre_correcto = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $nombre_v1);

        return $nombre_correcto;
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

    public function defaultItems()
    {
        return $this->hasMany('App\DefaultItem');
    }

    public function items()
    {
        return $this->hasMany('App\Item');
    }

    public function detailEntries()
    {
        return $this->hasMany('App\DetailEntry');
    }

    public function detailOutputs()
    {
        return $this->hasMany('App\OutputDetail');
    }

    protected $dates = ['deleted_at'];

    public function toArray()
    {
        $array = parent::toArray();
        $array['full_description'] = $this->full_description;
        return $array;
    }
}
