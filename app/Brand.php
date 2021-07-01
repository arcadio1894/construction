<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;

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
