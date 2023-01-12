<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = [
        'year',
        'date_complete',
        'description'
    ];

    protected $dates = ['created_at', 'updated_at', 'date_complete'];
}
