<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exampler extends Model
{
    protected $fillable = ['name','comment', 'brand_id'];

    public function materials()
    {
        return $this->hasMany('App\Material');
    }

    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }
}
