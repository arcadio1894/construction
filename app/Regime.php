<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regime extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'active',
    ];

    public function details()
    {
        return $this->hasMany('App\RegimeDetail');
    }

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
