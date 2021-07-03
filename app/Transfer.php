<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $fillable = [
        'code',
        'destination_location',
        'state'
    ];

    public function destinationLocation()
    {
        return $this->belongsTo('App\Location', 'destination_location');
    }

    public function details()
    {
        return $this->hasMany('App\TransferDetail');
    }
}
