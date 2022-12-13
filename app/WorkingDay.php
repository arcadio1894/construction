<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkingDay extends Model
{
    protected $fillable = [
        'description',
        'time_start',
        'time_fin',
        'enable'
    ];

    public function assistance_details()
    {
        return $this->hasMany('App\AssistanceDetail');
    }

}
