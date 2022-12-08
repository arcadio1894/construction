<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkFunction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'description',
        'enable'
    ];

    protected $dates = ['deleted_at'];
}
