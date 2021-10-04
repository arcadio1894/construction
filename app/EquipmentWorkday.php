<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipmentWorkday extends Model
{
    protected $fillable = [
        'equipment_id',
        'quantityPerson',
        'hoursPerPerson',
        'pricePerHour',
        'total'
    ];

    public function equipment(){
        return $this->belongsTo('App\Equipment');
    }
}
