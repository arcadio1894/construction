<?php

namespace App;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['examplers'];

    protected $fillable = ['name','comment'];

    public function materials()
    {
        return $this->hasMany('App\Material');
    }

    public function examplers()
    {
        return $this->hasMany('App\Exampler');
    }

    protected $dates = ['deleted_at'];
}
