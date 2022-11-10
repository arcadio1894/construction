<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityWorker extends Model
{
    protected $fillable = [
        'id',
        'activity_id',
        'worker_id',
        'hours_plan',
        'hours_real'
    ];

    public function activity()
    {
        return $this->belongsTo('App\Activity');
    }

    public function worker()
    {
        return $this->belongsTo('App\Worker');
    }
}
