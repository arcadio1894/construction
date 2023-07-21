<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Projection extends Model
{
    protected $fillable = [
        'year',
        'month',
        'projection_month_soles',
        'projection_month_dollars',
        'difference_soles',
        'difference_dollars',
        'projection_week_soles',
        'projection_week_dollars'
    ];

    public function details()
    {
        return $this->hasMany('App\ProjectionDetail');
    }
}
