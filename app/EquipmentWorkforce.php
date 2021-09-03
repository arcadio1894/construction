<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipmentWorkforce extends Model
{
    protected $fillable = [
        'equipment_id',
        'description',
        'price',
        'quantity',
        'total',
        'unit'
    ];

    public function equipment(){
        return $this->belongsTo('App\Equipment');
    }
}
