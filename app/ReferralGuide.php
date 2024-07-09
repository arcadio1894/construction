<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferralGuide extends Model
{
    protected $fillable = [
        'date_transfer',
        'reason_transfer_id',
        'customer_id',
        'supplier_id',
        'receiver',
        'document',
        'arrival_point',
        'placa',
        'driver',
        'driver_licence',
        'shipping_manager_id',
        'enabled_status',
        'code'
    ];

    protected $dates = ['date_transfer'];

    public function reason()
    {
        return $this->belongsTo('App\ReasonTransfer');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    public function responsible()
    {
        return $this->belongsTo('App\ShippingManager');
    }

    public function details()
    {
        return $this->hasMany('App\ReferralGuideDetail');
    }
}
