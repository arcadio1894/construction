<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentMaterial extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'equipment_id',
        'equipment_id',
        'quantity',
        'unit_price',
        'long',
        'width',
        'kilos',
        'percentage',
        'state',
        'price',
        'availability'
    ];

    protected $dates = ['deleted_at'];
}
