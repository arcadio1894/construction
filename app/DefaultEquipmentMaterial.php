<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefaultEquipmentMaterial extends Model
{
    protected $fillable = [
        'default_equipment_id',
        'material_id',
        'quantity',
        'length',
        'width',
        'percentage',
        'unit_price',
        'total_price'
    ];

    public function default_equipment(){
        return $this->belongsTo('App\DefaultEquipment');
    }

    public function material(){
        return $this->belongsTo('App\Material');
    }
}
