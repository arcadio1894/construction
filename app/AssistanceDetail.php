<?php

namespace App;

use Carbon\Carbon;
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
        'working_day_id',
        'hours_discount'
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

    public function getHourOutNewAttribute($value)
    {
        $hourOutSend = $this->hour_out;
        $hourOut = Carbon::parse($this->hour_out);

        // Si la hora de salida es "00:00", sumamos 24 horas
        if ($hourOut->format('H:i') == '00:00') {
            $hourOut->addDay();
            $hourOutSend = $hourOut;
        }

        return $hourOutSend;
    }

}
