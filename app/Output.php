<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Output extends Model
{
    protected $fillable = [
        'execution_order',
        'request_date',
        'requesting_user',
        'responsible_user',
        'state',
        'indicator'
    ];

    public function details()
    {
        return $this->hasMany('App\OutputDetail');
    }

    public function requestingUser()
    {
        return $this->belongsTo('App\User', 'requesting_user');
    }

    public function responsibleUser()
    {
        return $this->belongsTo('App\User', 'responsible_user');
    }

    public function quote()
    {
        return $this->belongsTo('App\Quote', 'execution_order', 'order_execution');
    }
}
