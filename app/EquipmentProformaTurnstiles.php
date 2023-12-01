<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipmentProformaTurnstiles extends Model
{
    protected $fillable = [
        'equipment_proforma_id',
        'description',
        'unit_price',
        'quantity',
        'total_price'
    ];

    public function equipment(){
        return $this->belongsTo('App\EquipmentProforma', 'equipment_proforma_id');
    }
}
