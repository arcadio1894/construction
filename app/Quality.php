<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quality extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description'
    ];

    public function materials()
    {
        return $this->hasMany('App\Material');
    }

    protected $dates = ['deleted_at'];
}
