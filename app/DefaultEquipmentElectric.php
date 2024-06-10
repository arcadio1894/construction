<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefaultEquipmentElectric extends Model
{
    protected $table = 'default_equipment_electrics';

    protected $fillable = [
        'default_equipment_id',
        'material_id',
        'quantity',
        'price',
        'total'
    ];

    public function default_equipment(){
        return $this->belongsTo('App\DefaultEquipment');
    }

    public function material(){
        return $this->belongsTo('App\Material');
    }
}
