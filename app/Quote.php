<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    protected $appends = ['subtotal_utility', 'subtotal_letter', 'subtotal_rent'];

    protected $fillable = [
        'code',
        'description_quote',
        'description',
        'date_quote',
        'way_to_pay',
        'delivery_time',
        'customer_id',
        'date_validate',
        'total',
        'state',
        'utility',
        'letter',
        'rent'
    ];

    public function getSubtotalUtilityAttribute()
    {
        $subtotal1 = $this->total * (($this->utility/100)+1);
        return number_format($subtotal1, 2);
    }

    public function getSubtotalLetterAttribute()
    {
        $subtotal1 = $this->total * (($this->utility/100)+1);
        $subtotal2 = $subtotal1 * (($this->letter/100)+1);
        return number_format($subtotal2, 2);
    }

    public function getSubtotalRentAttribute()
    {
        $subtotal1 = $this->total * (($this->utility/100)+1);
        $subtotal2 = $subtotal1 * (($this->letter/100)+1);
        $subtotal3 = $subtotal2 * (($this->rent/100)+1);
        return number_format($subtotal3, 0);
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function equipments()
    {
        return $this->hasMany('App\Equipment');
    }

}
