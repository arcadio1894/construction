<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderPurchase extends Model
{
    public $fillable = [
        'code',
        'supplier_id',
        'date_arrival',
        'date_order',
        'approved_by',
        'payment_condition',
        'currency_order',
        'currency_compra',
        'currency_venta',
        'igv',
        'total',
        'observation',
        'type'
    ];

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    public function approved_user()
    {
        return $this->belongsTo('App\User', 'approved_by');
    }
}
