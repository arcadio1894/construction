<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialOrder extends Model
{
    protected $fillable = [
        'material_id', 'quantity_request', 'quantity_entered'
    ];

    public function material()
    {
        return $this->belongsTo('App\Material');
    }
}
