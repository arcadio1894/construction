<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefaultEquipmentTurnstile extends Model
{
    protected $fillable = [
        'default_equipment_id',
        'description',
        'unit_price',
        'quantity',
        'total_price'
    ];

    public function default_equipment(){
        return $this->belongsTo('App\DefaultEquipment');
    }
}
