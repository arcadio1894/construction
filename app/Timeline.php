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

    protected $dates = ['deleted_at'];
}
