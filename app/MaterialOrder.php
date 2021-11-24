<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialOrder extends Model
{
    protected $fillable = [
        'order_purchase_detail_id', 'material_id', 'quantity_request', 'quantity_entered'
    ];

    public function material()
    {
        return $this->belongsTo('App\Material');
    }

    public function detail()
    {
        return $this->belongsTo('App\OrderPurchaseDetail');
    }
}
