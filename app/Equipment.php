<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    protected $table = 'equipments';

    protected $appends = ['subtotal_rent', 'subtotal_utility', 'subtotal_percentage'];

    protected $fillable = [
        'quote_id',
        'description',
        'detail',
        'quantity',
        'total',
        'utility',
        'letter',
        'rent',
        'finished'
    ];

    public function getSubtotalUtilityAttribute()
    {
        if ( $this->quote->total_soles != 0 )
        {
            $total_soles = $this->total * $this->quote->currency_venta;
            $subtotal1 = $total_soles * (($this->utility/100)+1);
            return $subtotal1;
        } else {
            $subtotal1 = $this->total * (($this->utility/100)+1);
            return $subtotal1;
        }

    }

    public function getSubtotalPercentageAttribute()
    {
        if ( $this->quote->total_soles != 0 )
        {
            $total_soles = $this->total * $this->quote->currency_venta;
            $subtotal1 = $total_soles * (($this->utility/100)+1);
            $subtotal2 = $subtotal1 * (($this->letter/100)+1);
            $subtotal3 = $subtotal2 * (($this->rent/100)+1);
            return $subtotal3;
        } else {
            $subtotal1 = $this->total * (($this->utility/100)+1);
            $subtotal2 = $subtotal1 * (($this->letter/100)+1);
            $subtotal3 = $subtotal2 * (($this->rent/100)+1);
            return $subtotal3;
        }

    }

    public function getSubtotalRentAttribute()
    {
        if ( $this->utility == 0 && $this->letter == 0 && $this->rent == 0 )
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
        } else {
            if ( $this->quote->total_soles != 0 )
            {
                $total_soles = $this->total * $this->quote->currency_venta;
                $subtotal1 = $total_soles * (($this->utility/100)+1);
                $subtotal2 = $subtotal1 * (($this->letter/100)+1);
                $subtotal3 = $subtotal2 * (($this->rent/100)+1);
                return $subtotal3;
            } else {
                $subtotal1 = $this->total * (($this->utility/100)+1);
                $subtotal2 = $subtotal1 * (($this->letter/100)+1);
                $subtotal3 = $subtotal2 * (($this->rent/100)+1);
                return $subtotal3;
            }
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

    public function getTotalMaterialsAttribute()
    {
        $total = 0;
        foreach ( $this->materials as $material )
        {
            $total += $material->total;
        }

        return $total*$this->quantity;

    }

    public function getTotalConsumablesAttribute()
    {
        $total = 0;
        foreach ( $this->consumables as $consumable )
        {
            $total += $consumable->total;
        }

        return $total*$this->quantity;

    }

    public function getTotalWorkforcesAttribute()
    {
        $total = 0;
        foreach ( $this->workforces as $workforce )
        {
            $total += $workforce->total;
        }

        return $total*$this->quantity;

    }

    public function getTotalTurnstilesAttribute()
    {
        $total = 0;
        foreach ( $this->turnstiles as $turnstile )
        {
            $total += $turnstile->total;
        }

        return $total*$this->quantity;

    }

    public function getTotalWorkdaysAttribute()
    {
        $total = 0;
        foreach ( $this->workdays as $workday )
        {
            $total += $workday->total;
        }

        return $total;

    }
}
