<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinishContract extends Model
{
    protected $fillable = [
        'worker_id',
        'contract_id',
        'date_finish',
        'reason',
        'active'
    ];

    protected $dates = ['date_finish'];

    public function worker()
    {
        return $this->belongsTo('App\Worker');
    }

    public function contract()
    {
        return $this->belongsTo('App\Contract');
    }
}
