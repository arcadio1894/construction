<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    protected $fillable = [
        'code',
        'description_quote',
        'description',
        'date_quote',
        'way_to_pay',
        'delivery_time',
        'customer_id',
        'date_validate',
        'total',
        'state'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function equipments()
    {
        return $this->hasMany('App\Equipment');
    }

}
