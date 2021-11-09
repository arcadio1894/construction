<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentFeature extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'equipment_id',
        'description'
    ];

    public function equipment()
    {
        return $this->belongsTo('App\Equipment');
    }

    protected $dates = ['deleted_at'];
}
