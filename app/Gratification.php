<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gratification extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reason',
        'date',
        'amount',
        'worker_id',
        'grati_period_id'
    ];

    protected $dates = ['deleted_at'];

    public function worker()
    {
        return $this->belongsTo('App\Worker');
    }

    public function period()
    {
        return $this->belongsTo('App\GratiPeriod');
    }
}
