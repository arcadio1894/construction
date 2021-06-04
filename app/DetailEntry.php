<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailEntry extends Model
{
    protected $fillable = [
        'entry_id',
        'material_id',
        'ordered_quantity',
        'entered_quantity',
        'isComplete'
    ];

    public function entry()
    {
        return $this->belongsTo('App\Entry');
    }

    public function material()
    {
        return $this->belongsTo('App\Material');
    }

    public function items()
    {
        return $this->hasMany('App\Item');
    }
}
