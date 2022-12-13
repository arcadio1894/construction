<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssistanceDetail extends Model
{
    protected $fillable = [
        'date_assistance',
        'hour_entry',
        'hour_out',
        'status',
        'justification',
        'obs_justification',
        'worker_id',
        'assistance_id',
        'working_day_id'
    ];

    protected $dates = ['date_assistance'];

    public function worker()
    {
        return $this->belongsTo('App\Worker');
    }

    public function assistance()
    {
        return $this->belongsTo('App\Assistance');
    }

    public function working_day()
    {
        return $this->belongsTo('App\WorkingDay');
    }

}
