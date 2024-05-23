<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResumenEquipment extends Model
{
    protected $table = "resumen_equipments";

    protected $fillable = [
        'resumen_quote_id',
        'equipment_id',
        'description',
        'total_materials',
        'total_consumables',
        'total_electrics',
        'total_workforces',
        'total_turnstiles',
        'total_workdays',
        'quantity',
        'total',
        'utility',
        'letter',
        'rent'
    ];

    public function resumen()
    {
        return $this->belongsTo('App\ResumenQuote', 'resumen_quote_id');
    }

    public function equipment()
    {
        return $this->belongsTo('App\Equipment');
    }

}
