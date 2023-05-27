<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierCredit extends Model
{
    protected $fillable = [
        'supplier_id',
        'invoice',
        'image_invoice',
        'total_soles',
        'total_dollars',
        'date_issue',
        'date_expiration',
        'days_to_expiration',
        'observation',
        'observation_extra',
        'order_purchase_id',
        'state_credit',
        'order_service_id',
        'code_order',
        'payment_deadline_id',
        'image_credit',
        'date_paid',
        'state_pay',
        'advance'
    ];

    protected $dates=['date_issue', 'date_expiration', 'date_paid'];

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    public function purchase()
    {
        return $this->belongsTo('App\OrderPurchase', 'order_purchase_id');
    }

    public function service()
    {
        return $this->belongsTo('App\OrderService', 'order_service_id');
    }

    public function deadline()
    {
        return $this->belongsTo('App\PaymentDeadline', 'payment_deadline_id','id');
    }
}
