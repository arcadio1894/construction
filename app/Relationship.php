<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    protected $fillable = [
        'description'
    ];

    public function emergency_contacts()
    {
        return $this->hasMany('App\EmergencyContact');
    }
}
