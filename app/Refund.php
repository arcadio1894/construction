<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refund extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reason',
        'date',
        'amount',
        'worker_id'
    ];

    protected $dates = ['deleted_at', 'date'];

    public function worker()
    {
        return $this->belongsTo('App\Worker');
    }
}
