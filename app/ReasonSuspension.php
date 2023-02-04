<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReasonSuspension extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reason',
        'days',
    ];

    protected $dates = ['deleted_at'];
}
