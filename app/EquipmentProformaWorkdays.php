<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipmentProformaWorkdays extends Model
{
    protected $fillable = [
        'description',
        'equipment_proforma_id',
        'quantityPerson',
        'hoursPerPerson',
        'pricePerHour',
        'total_price'
    ];

    public function equipment(){
        return $this->belongsTo('App\EquipmentProforma', 'equipment_proforma_id');
    }
}
