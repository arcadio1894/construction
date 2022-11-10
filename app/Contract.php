<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'code',
        'date_start',
        'date_fin',
    ];

    protected $dates = ['deleted_at'];
}
