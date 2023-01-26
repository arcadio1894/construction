<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'time'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
