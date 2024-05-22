<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipmentElectric extends Model
{
    protected $fillable = [
        'equipment_id',
        'material_id',
        'quantity',
        'price',
        'total',
    ];

    public function equipment(){
        return $this->belongsTo('App\Equipment');
    }

    public function material(){
        return $this->belongsTo('App\Material');
    }
}
