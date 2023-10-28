<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefaultEquipmentWorkDay extends Model
{
    protected $fillable = [
        'default_equipment_id',
        'equipment_id',
        'quantityPerson',
        'hoursPerPerson',
        'pricePerHour',
        'total_price'
    ];

    public function default_equipment(){
        return $this->belongsTo('App\DefaultEquipment');
    }
}
