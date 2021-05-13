<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'quote_id',
        'description',
        'total'
    ];

    public function quote()
    {
        return $this->belongsTo('App\Quote');
    }

    public function materials()
    {
        return $this->belongsToMany('App\Material', 'equipment_materials')->withPivot('product_id', 'quantity', 'unit_price');
    }

    protected $dates = ['deleted_at'];
}
