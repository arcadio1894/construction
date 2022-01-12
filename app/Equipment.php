<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    protected $table = 'equipments';

    protected $appends = ['subtotal_rent'];

    protected $fillable = [
        'quote_id',
        'description',
        'detail',
        'quantity',
        'total'
    ];

    public function getSubtotalRentAttribute()
    {
        if ( $this->quote->total_soles != 0 )
        {
            $total_soles = $this->total * $this->quote->currency_venta;
            $subtotal1 = $total_soles * (($this->quote->utility/100)+1);
            $subtotal2 = $subtotal1 * (($this->quote->letter/100)+1);
            $subtotal3 = $subtotal2 * (($this->quote->rent/100)+1);
            return $subtotal3;
        } else {
            $subtotal1 = $this->total * (($this->quote->utility/100)+1);
            $subtotal2 = $subtotal1 * (($this->quote->letter/100)+1);
            $subtotal3 = $subtotal2 * (($this->quote->rent/100)+1);
            return $subtotal3;
        }

    }

    public function quote()
    {
        return $this->belongsTo('App\Quote');
    }

    public function materials()
    {
        return $this->hasMany('App\EquipmentMaterial');
    }

    public function consumables()
    {
        return $this->hasMany('App\EquipmentConsumable');
    }

    public function workforces()
    {
        return $this->hasMany('App\EquipmentWorkforce');
    }

    public function turnstiles()
    {
        return $this->hasMany('App\EquipmentTurnstile');
    }

    public function workdays()
    {
        return $this->hasMany('App\EquipmentWorkday');
    }
}
