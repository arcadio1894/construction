<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipmentTurnstile extends Model
{
    protected $fillable = [
        'equipment_id',
        'description',
        'price',
        'quantity',
        'total'
    ];

    public function equipment(){
        return $this->belongsTo('App\Equipment');
    }
}
