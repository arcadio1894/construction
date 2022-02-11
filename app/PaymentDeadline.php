<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentDeadline extends Model
{
    protected $fillable = [
        'description',
        'days',
        'type',
        'credit'
    ];

    public function quotes()
    {
        return $this->hasMany('App\Quote');
    }

    public function orderPurchases()
    {
        return $this->hasMany('App\OrderPurchase');
    }

    public function orderServices()
    {
        return $this->hasMany('App\OrderService');
    }
}
