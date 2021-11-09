<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipmentConsumable extends Model
{
    protected $fillable = [
        'equipment_id',
        'material_id',
        'quantity',
        'price',
        'total',
        'availability',
        'state'
    ];

    public function equipment(){
        return $this->belongsTo('App\Equipment');
    }

    public function material(){
        return $this->belongsTo('App\Material');
    }
}
