<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentMaterial extends Model
{
    protected $fillable = [
        'equipment_id',
        'material_id',
        'quantity',
        'price',
        'length',
        'width',
        'percentage',
        'state',
        'total',
        'availability'
    ];

    public function equipment(){
        return $this->belongsTo('App\Equipment');
    }

    public function material(){
        return $this->belongsTo('App\Material');
    }

}
