<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = [
        'name',
        'comment',
        'shelf_id'
    ];

    public function shelf()
    {
        return $this->belongsTo('App\Shelf');
    }

    public function containers()
    {
        return $this->hasMany('App\Container');
    }

    public function locations()
    {
        return $this->hasMany('App\Location');
    }
}
