<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    protected $appends = ['subtotal_utility', 'subtotal_letter', 'subtotal_rent', 'subtotal_utility_edit', 'subtotal_letter_edit', 'subtotal_rent_edit'];

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
        'rent',
        'code_customer',
        'raise_status',
        'currency_invoice',
        'currency_compra',
        'currency_venta',
        'total_soles',
        'order_execution',
        'state_active'
    ];

    public function getSubtotalUtilityAttribute()
    {
        if ( $this->total_soles != 0 )
        {
            $subtotal1 = $this->total_soles * (($this->utility/100)+1);
            return number_format($subtotal1, 2);
        } else {
            $subtotal1 = $this->total * (($this->utility/100)+1);
            return number_format($subtotal1, 2);
        }

    }

    public function getSubtotalUtilityEditAttribute()
    {
        $subtotal1 = $this->total * (($this->utility/100)+1);
        return number_format($subtotal1, 2);

    }

    public function getSubtotalLetterAttribute()
    {
        if ( $this->total_soles != 0 )
        {
            $subtotal1 = $this->total_soles * (($this->utility/100)+1);
            $subtotal2 = $subtotal1 * (($this->letter/100)+1);
            return number_format($subtotal2, 2);
        } else {
            $subtotal1 = $this->total * (($this->utility/100)+1);
            $subtotal2 = $subtotal1 * (($this->letter/100)+1);
            return number_format($subtotal2, 2);
        }

    }

    public function getSubtotalLetterEditAttribute()
    {
        $subtotal1 = $this->total * (($this->utility/100)+1);
        $subtotal2 = $subtotal1 * (($this->letter/100)+1);
        return number_format($subtotal2, 2);
    }

    public function getSubtotalRentAttribute()
    {
        if ( $this->total_soles != 0 )
        {
            $subtotal1 = $this->total_soles * (($this->utility/100)+1);
            $subtotal2 = $subtotal1 * (($this->letter/100)+1);
            $subtotal3 = $subtotal2 * (($this->rent/100)+1);
            return number_format($subtotal3, 0);
        } else {
            $subtotal1 = $this->total * (($this->utility/100)+1);
            $subtotal2 = $subtotal1 * (($this->letter/100)+1);
            $subtotal3 = $subtotal2 * (($this->rent/100)+1);
            return number_format($subtotal3, 0);
        }

    }

    public function getSubtotalRentEditAttribute()
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
