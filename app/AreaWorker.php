<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AreaWorker extends Model
{
    protected $fillable = [
        'name',
    ];

    public function workers()
    {
        return $this->hasMany('App\Worker');
    }
}
