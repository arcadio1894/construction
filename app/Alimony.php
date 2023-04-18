<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alimony extends Model
{
    protected $appends = ['name_month'];

    protected $fillable = [
        'week',
        'month',
        'year',
        'date',
        'amount',
        'worker_id',
    ];

    protected $dates = ['date'];

    public function getNameMonthAttribute()
    {
        $fecha = DateDimension::where('date', $this->date)->first();
        $nombre_mes = $fecha->month_name;
        return $nombre_mes;
    }

    public function worker()
    {
        return $this->belongsTo('App\Worker');
    }

}
