<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SueldoMensual extends Model
{
    protected $fillable = [
        'year',
        'month',
        'nameMonth',
        'shortName',
        'total',
    ];

}
