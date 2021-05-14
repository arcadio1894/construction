<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name','description'];

    public function materials()
    {
        return $this->hasMany('App\Material');
    }
}
