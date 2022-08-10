<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialTaken extends Model
{
    protected $fillable = [
        'material_id',
        'quantity_request',
        'quote_id',
        'output_id',
        'equipment_id',
        'output_detail_id',
        'type_output'
    ];

    public function material()
    {
        return $this->belongsTo('App\Material');
    }

    public function quote()
    {
        return $this->belongsTo('App\Quote');
    }

    public function output()
    {
        return $this->belongsTo('App\Output');
    }
}
