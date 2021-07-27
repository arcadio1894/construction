<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Typescrap extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'length',
        'width'
    ];

    public function materials()
    {
        return $this->hasMany('App\Material');
    }


    protected $dates = ['deleted_at'];
}
