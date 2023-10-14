<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'business_name',
        'RUC',
        'code',
        'address',
        'phone',
        'email',
        'special'
    ];

    protected $dates = ['deleted_at'];

    // TODO: Agregar una relacion entre Supplier y SupplierAccount De uno a muchos
}
