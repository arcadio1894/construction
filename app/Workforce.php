<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workforce extends Model
{
    protected $fillable = [
        'description',
        'unit_measure_id',
        'unit_price'
    ];

    public function unitMeasure()
    {
        return $this->belongsTo('App\UnitMeasure');
    }
}
