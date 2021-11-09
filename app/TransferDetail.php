<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferDetail extends Model
{

    protected $fillable = [
        'transfer_id',
        'item_id',
        'origin_location',
    ];

    public function transfer()
    {
        return $this->belongsTo('App\Transfer');
    }

    public function item()
    {
        return $this->belongsTo('App\Item');
    }

    public function originLocation()
    {
        return $this->belongsTo('App\Location', 'origin_location');
    }

}
