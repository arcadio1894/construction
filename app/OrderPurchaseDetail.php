<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderPurchaseDetail extends Model
{
    public $fillable = [
        'order_purchase_id',
        'material_id',
        'quantity',
        'price',
        'igv'
    ];

    public function order_purchase()
    {
        return $this->belongsTo('App\OrderPurchase');
    }

    public function material()
    {
        return $this->belongsTo('App\Material');
    }
}
