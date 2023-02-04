<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suspension extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'date_start',
        'date_end',
        'worker_id',
        'reason_suspension_id'
    ];

    protected $dates = ['deleted_at', 'date_start', 'date_end'];

    public function worker()
    {
        return $this->belongsTo('App\Worker');
    }

    public function reason()
    {
        return $this->belongsTo('App\ReasonSuspension');
    }

}
