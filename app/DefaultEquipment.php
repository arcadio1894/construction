<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DefaultEquipment extends Model
{
    protected $table = 'default_equipments';

    protected $fillable = [
        'description',
        'large',
        'width',
        'high',
        'category_equipment_id',
        'details',
        'utility',
        'letter',
        'rent'
    ];

    protected $appends = ['subtotal_rent', 'subtotal_utility', 'subtotal_percentage'];

    public function category()
    {
        return $this->belongsTo('App\CategoryEquipment');
    }

    /*public function getSubtotalUtilityAttribute()
    {
        if ( $this->pre_quote->total_soles != 0 )
        {
            $total_soles = $this->total * $this->pre_quote->currency_venta;
            $subtotal1 = $total_soles * (($this->utility/100)+1);
            return $subtotal1;
        } else {
            $subtotal1 = $this->total * (($this->utility/100)+1);
            return $subtotal1;
        }

    }

    public function getSubtotalPercentageAttribute()
    {
        if ( $this->pre_quote->total_soles != 0 )
        {
            $total_soles = $this->total * $this->pre_quote->currency_venta;
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
        if ( $this->pre_quote->total_soles != 0 )
        {
            $total_soles = $this->total * $this->pre_quote->currency_venta;
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

    public function pre_quote()
    {
        return $this->belongsTo('App\PreQuote');
    }*/

    public function materials()
    {
        return $this->hasMany('App\DefaultEquipmentMaterial');
    }

    public function consumables()
    {
        return $this->hasMany('App\DefaultEquipmentConsumable');
    }

    public function workforces()
    {
        return $this->hasMany('App\DefaultEquipmentWorkforce');
    }

    public function turnstiles()
    {
        return $this->hasMany('App\DefaultEquipmentTurnstile');
    }

    public function workdays()
    {
        return $this->hasMany('App\DefaultEquipmentWorkday');
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

        return $total*$this->quantity;

    }
}