<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GratiPeriod extends Model
{
    protected $fillable = [
        'description',
        'month',
        'year'
    ];

    public function gratifications()
    {
        return $this->hasMany('App\Gratification');
    }
}
