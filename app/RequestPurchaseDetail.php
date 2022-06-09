<?php

namespace App;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestPurchaseDetail extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['request_purchases'];

    protected $fillable = ['request_purchase_id','material_id', 'quantity'];

    public function requestPurchase()
    {
        return $this->belongsTo('App\RequestPurchase');
    }

    public function material()
    {
        return $this->belongsTo('App\Material');
    }

    protected $dates = ['deleted_at'];
}
