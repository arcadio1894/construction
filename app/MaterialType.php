<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialType extends Model
{
    protected $fillable = ['name','length', 'width','weight'];

    public function materials()
    {
        return $this->hasMany('App\Material');
    }
}
