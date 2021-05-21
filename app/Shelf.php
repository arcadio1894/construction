<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    protected $fillable = [
        'name',
        'comment',
        'warehouse_id'
    ];

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse');
    }

    public function levels()
    {
        return $this->hasMany('App\Level');
    }

    public function locations()
    {
        return $this->hasMany('App\Location');
    }
}
