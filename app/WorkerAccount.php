<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkerAccount extends Model
{
    protected $fillable = [
        'worker_id',
        'number_account',
        'currency',
        'bank_id'
    ];

    public function worker()
    {
        return $this->hasMany('App\Worker');
    }
    public function bank()
    {
        return $this->hasMany('App\Bank');
    }

}
