<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinanceWork extends Model
{
    protected $fillable = [
        'quote_id',
        'raise_date',
        'date_delivery',
        'act_of_acceptance',
        'state_act_of_acceptance',
        'advancement',
        'amount_advancement',
        'detraction',
        'invoiced',
        'number_invoice',
        'month_invoice',
        'date_issue',
        'date_admission',
        'bank_id',
        'state',
        'date_paid',
        'observation'
    ];

    protected $dates = ['raise_date', 'date_issue', 'date_admission', 'date_paid', 'date_delivery'];

    public function quote()
    {
        return $this->belongsTo('App\Quote');
    }

    public function bank()
    {
        return $this->belongsTo('App\Bank');
    }
}
