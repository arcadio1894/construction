<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'quote_id',
        'description',
        'total'
    ];

    public function quote()
    {
        return $this->belongsTo('App\Quote');
    }

    public function materials()
    {
        return $this->belongsToMany('App\Material', 'equipment_materials')
            ->withPivot('material_id', 'quantity', 'unit_price', 'long', 'width', 'kilos', 'percentage', 'state', 'price', 'availability');
    }

    public function defaultItem()
    {
        return $this->hasMany('App\DefaultItem');
    }

    public function equipmentFeatures()
    {
        return $this->hasMany('App\EquipmentFeature');
    }

    protected $dates = ['deleted_at'];
}
