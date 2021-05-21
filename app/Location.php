<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'area_id',
        'warehouse_id',
        'shelf_id',
        'level_id',
        'container_id',
        'description'
    ];

    public function area()
    {
        return $this->belongsTo('App\Area');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse');
    }

    public function shelf()
    {
        return $this->belongsTo('App\Shelf');
    }

    public function level()
    {
        return $this->belongsTo('App\Level');
    }

    public function container()
    {
        return $this->belongsTo('App\Container');
    }
}
