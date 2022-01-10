<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderServiceDetail extends Model
{
    public $fillable = [
        'order_service_id',
        'service',
        'unit',
        'quantity',
        'price',
        'igv'
    ];

    public function order_service()
    {
        return $this->belongsTo('App\OrderService');
    }

}
