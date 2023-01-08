<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmergencyContact extends Model
{
    protected $fillable = [
        'name',
        'relationship_id',
        'worker_id',
        'phone'
    ];

    public function worker()
    {
        return $this->belongsTo('App\Worker');
    }

    public function relationship()
    {
        return $this->belongsTo('App\Relationship');
    }
}
