<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoCambio extends Model
{
    protected $fillable = [
        'fecha',
        'precioCompra',
        'precioVenta'
    ];

    protected $dates = ['fecha'];
}
