<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reason',
        'date',
        'num_dues',
        'amount_total',
        'time_pay',
        'rate',
        'worker_id'
    ];

    protected $dates = ['deleted_at', 'date'];

    public function worker()
    {
        return $this->belongsTo('App\Worker');
    }

    public function dues()
    {
        return $this->hasMany('App\Due');
    }
}
