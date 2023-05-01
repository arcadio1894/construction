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
        'type'
    ];

    protected $dates = ['date'];

    public function getNameMonthAttribute()
    {
        $months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $mes = $this->month;
        $nombre_mes = $months[$mes-1];
        return $nombre_mes;
    }

    public function worker()
    {
        return $this->belongsTo('App\Worker');
    }

}
