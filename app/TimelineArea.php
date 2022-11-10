<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimelineArea extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'area'
    ];

    protected $dates = ['deleted_at'];
}
