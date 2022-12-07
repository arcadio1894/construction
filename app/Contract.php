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
        'file'
    ];

    protected $dates = ['deleted_at', 'date_start', 'date_fin'];
}
