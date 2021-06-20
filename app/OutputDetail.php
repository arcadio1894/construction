<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutputDetail extends Model
{
    protected $fillable = [
        'output_id',
        'item_id'
    ];

    public function output()
    {
        return $this->belongsTo('App\Output');
    }

    public function items()
    {
        return $this->hasMany('App\Item');
    }
}
