<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutputDetail extends Model
{
    protected $fillable = [
        'output_id',
        'item_id',
        'length',
        'width',
        'price',
        'percentage',
        'material_id',
        'equipment_id',
        'quote_id',
        'custom'
    ];

    public function output()
    {
        return $this->belongsTo('App\Output');
    }

    public function items()
    {
        return $this->belongsTo('App\Item', 'item_id', 'id');
    }

    public function material()
    {
        return $this->belongsTo('App\Material');
    }

    public function equipment()
    {
        return $this->belongsTo('App\Equipment');
    }

    public function quote()
    {
        return $this->belongsTo('App\Quote');
    }
}
