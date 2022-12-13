<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assistance extends Model
{
    protected $fillable = [
        'date_assistance',
    ];

    protected $dates = ['date_assistance'];

    public function details()
    {
        return $this->hasMany('App\AssistanceDetail');
    }
}
