<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'name',
        'comment'
    ];

    public function warehouses()
    {
        return $this->hasMany('App\Warehouse');
    }

    public function locations()
    {
        return $this->hasMany('App\Location');
    }
}
