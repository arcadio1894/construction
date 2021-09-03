<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    protected $table = 'equipments';

    protected $fillable = [
        'quote_id',
        'description',
        'detail',
        'quantity',
        'total'
    ];

    public function quote()
    {
        return $this->belongsTo('App\Quote');
    }

    public function materials()
    {
        return $this->hasMany('App\EquipmentMaterial');
    }

    public function consumables()
    {
        return $this->hasMany('App\EquipmentConsumable');
    }

    public function workforces()
    {
        return $this->hasMany('App\EquipmentWorkforce');
    }
}
