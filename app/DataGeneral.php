<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataGeneral extends Model
{
    protected $fillable = [
        'name',
        'valueText',
        'valueNumber',
        'module',
        'description'
    ];
}
