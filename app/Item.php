<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'detail_entry_id',
        'material_id',
        'code',
        'length',
        'width',
        'weight',
        'price',
        'material_type_id',
        'location_id',
        'state'
    ];

    public function detailEntry()
    {
        return $this->belongsTo('App\DetailEntry');
    }

    public function material()
    {
        return $this->belongsTo('App\Material');
    }

    public function materialType()
    {
        return $this->belongsTo('App\MaterialType');
    }

    public function location()
    {
        return $this->belongsTo('App\Location');
    }
}
