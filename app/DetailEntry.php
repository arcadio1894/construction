<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailEntry extends Model
{
    protected $appends = ['sub_total', 'taxes', 'total'];

    protected $fillable = [
        'entry_id',
        'material_id',
        'ordered_quantity',
        'entered_quantity',
        'isComplete',
        'unit_price'
    ];

    public function getSubTotalAttribute()
    {
        $number = ($this->entered_quantity * $this->unit_price)/1.18;
        return "S/. " . number_format($number, 2);
    }

    public function getTaxesAttribute()
    {
        $number = (($this->entered_quantity * $this->unit_price)/1.18)*0.18;
        return "S/. " . number_format($number, 2);
    }

    public function getTotalAttribute()
    {
        $number = $this->entered_quantity * $this->unit_price;
        return "S/. " . number_format($number, 2);
    }

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
