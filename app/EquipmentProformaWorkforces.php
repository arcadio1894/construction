<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipmentProformaWorkforces extends Model
{
    protected $fillable = [
        'equipment_proforma_id',
        'description',
        'unit_price',
        'quantity',
        'total_price',
        'unit'
    ];

    public function equipment(){
        return $this->belongsTo('App\EquipmentProforma', 'equipment_proforma_id');
    }
}
