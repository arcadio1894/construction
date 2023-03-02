<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Due extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'loan_id',
        'date',
        'num_due',
        'amount',
        'worker_id',
    ];

    protected $dates = ['deleted_at', 'date'];

    public function worker()
    {
        return $this->belongsTo('App\Worker');
    }

    public function loan()
    {
        return $this->belongsTo('App\Loan');
    }
}
