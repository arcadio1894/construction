<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipmentProformaConsumable extends Model
{
    protected $fillable = [
        'equipment_proforma_id',
        'material_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    public function equipment(){
        return $this->belongsTo('App\EquipmentProforma', 'equipment_proforma_id');
    }

    public function material(){
        return $this->belongsTo('App\Material');
    }
}
