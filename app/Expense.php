<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'bill_id',
        'worker_id',
        'date_expense',
        'week',
        'total',
    ];

    protected $dates = ['date_expense'];

    public function bill()
    {
        return $this->belongsTo('App\Bill');
    }

    public function worker()
    {
        return $this->belongsTo('App\Worker');
    }
}
