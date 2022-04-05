<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPurchaseDetail extends Model
{
    use SoftDeletes;

    public $fillable = [
        'order_purchase_id',
        'material_id',
        'quantity',
        'price',
        'igv',
        'total_detail'
    ];

    protected $dates = ['deleted_at'];

    public function order_purchase()
    {
        return $this->belongsTo('App\OrderPurchase');
    }

    public function material()
    {
        return $this->belongsTo('App\Material');
    }
}
