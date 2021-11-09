<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DefaultItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'equipment_id',
        'material_id',
        'extra',
        'quantity',
        'unit_measure',
        'unit_price',
        'total_price'
    ];

    protected $dates = ['deleted_at'];

    public function equipment()
    {
        return $this->belongsTo('App\Equipment');
    }

    public function material()
    {
        return $this->belongsTo('App\Material');
    }

    public function details()
    {
        return $this->hasMany('App\DetailItem');
    }
}
