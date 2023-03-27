<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegimeDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'regime_id',
        'working_day_id',
        'dayNumber',
        'dayName'
    ];

    public function regime()
    {
        return $this->belongsTo('App\Regime');
    }

    public function working_day()
    {
        return $this->belongsTo('App\WorkingDay');
    }

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

}
