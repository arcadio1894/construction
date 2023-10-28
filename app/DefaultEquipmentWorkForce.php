<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefaultEquipmentWorkForce extends Model
{
    protected $fillable = [
        'default_equipment_id',
        'description',
        'unit_price',
        'quantity',
        'total_price',
        'unit'
    ];

    public function default_equipment(){
        return $this->belongsTo('App\DefaultEquipment');
    }
}
