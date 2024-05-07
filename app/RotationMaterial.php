<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RotationMaterial extends Model
{
    protected $fillable = [
        'date_rotation',
        'user_id',
    ];

    protected $dates = ['date_rotation'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
