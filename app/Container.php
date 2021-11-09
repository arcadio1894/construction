<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    protected $fillable = [
        'name',
        'comment',
        'level_id'
    ];

    public function level()
    {
        return $this->belongsTo('App\Level');
    }

    public function positions()
    {
        return $this->hasMany('App\Position');
    }

    public function locations()
    {
        return $this->hasMany('App\Location');
    }

}
