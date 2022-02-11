<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderService extends Model
{
    public $fillable = [
        'code',
        'supplier_id',
        'date_delivery',
        'date_order',
        'approved_by',
        'payment_condition',
        'currency_order',
        'currency_compra',
        'currency_venta',
        'igv',
        'total',
        'observation',
        'quote_supplier',
        'regularize',
        'image_invoice',
        'image_observation',
        'deferred_invoice',
        'date_invoice',
        'referral_guide',
        'invoice',
        'payment_deadline_id'
    ];

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    public function approved_user()
    {
        return $this->belongsTo('App\User', 'approved_by');
    }

    public function details()
    {
        return $this->hasMany('App\OrderServiceDetail');
    }

    public function deadline()
    {
        return $this->belongsTo('App\PaymentDeadline', 'payment_deadline_id');
    }
}
