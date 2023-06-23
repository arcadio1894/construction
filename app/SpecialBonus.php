<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecialBonus extends Model
{
    protected $fillable = [
        'reason',
        'date',
        'amount',
        'worker_id',
        'week'
    ];

    protected $dates = ['deleted_at', 'date'];

    public function worker()
    {
        return $this->belongsTo('App\Worker');
    }
}
