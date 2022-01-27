<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierCredit extends Model
{
    protected $fillable = [
        'supplier_id',
        'entry_id',
        'invoice',
        'image_invoice',
        'purchase_order',
        'total_soles',
        'total_dollars',
        'date_issue',
        'date_expiration',
        'payment_deadline',
        'state',
        'days_to_expiration',
        'observation',
        'state_credit',
        'observation_extra'
    ];

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    public function entry()
    {
        return $this->belongsTo('App\Entry');
    }
}
