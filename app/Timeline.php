<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timeline extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'date',
        'turn',
        'responsible',
        'timeline_area_id'
    ];

    protected $dates = ['deleted_at', 'date', 'created_at', 'updated_at'];


    public function activities()
    {
        return $this->hasMany('App\Activity');
    }

    public function works()
    {
        return $this->hasMany('App\Work');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }


    public function responsibleUser()
    {
        return $this->belongsTo('App\Worker', 'responsible');
    }
}
