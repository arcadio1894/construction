<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Typescrap extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'length',
        'width'
    ];

    protected $dates = ['deleted_at'];
}
