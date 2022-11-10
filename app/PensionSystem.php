<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PensionSystem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'description',
        'percentage'
    ];

    protected $dates = ['deleted_at'];
}
