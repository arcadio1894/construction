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

    public function electrics()
    {
        return $this->hasMany('App\DefaultEquipmentElectric');
    }

    public function workforces()
    {
        return $this->hasMany('App\DefaultEquipmentWorkForce');
    }

    public function turnstiles()
    {
        return $this->hasMany('App\DefaultEquipmentTurnstile');
    }

    public function workdays()
    {
        return $this->hasMany('App\DefaultEquipmentWorkDay');
    }

    public function getTotalMaterialsAttribute()
    {
        $total = 0;
        foreach ( $this->materials as $material )
        {
            $total += $material->total_price;
        }

        return $total;

    }

    public function getTotalConsumablesAttribute()
    {
        $total = 0;
        foreach ( $this->consumables as $consumable )
        {
            $total += $consumable->total_price;
        }

        return $total;

    }

    public function getTotalElectricsAttribute()
    {
        $total = 0;
        foreach ( $this->electrics as $electric )
        {
            $total += $electric->total;
        }

        return $total;

    }

    public function getTotalWorkforcesAttribute()
    {
        $total = 0;
        foreach ( $this->workforces as $workforce )
        {
            $total += $workforce->total_price;
        }

        return $total;

    }

    public function getTotalTurnstilesAttribute()
    {
        $total = 0;
        foreach ( $this->turnstiles as $turnstile )
        {
            $total += $turnstile->total_price;
        }

        return $total;

    }

    public function getTotalWorkdaysAttribute()
    {
        $total = 0;
        foreach ( $this->workdays as $workday )
        {
            $total += $workday->total_price;
        }

        return $total;

    }

    public function getTotalEquipmentAttribute()
    {
        $total = $this->total_materials + $this->total_consumables + $this->total_electrics + $this->total_workforces + $this->total_turnstiles + $this->total_workdays;

        return $total;

    }

    public function getTotalEquipmentUtilityAttribute()
    {
        $total = $this->total_materials + $this->total_consumables + $this->total_electrics + $this->total_workforces + $this->total_turnstiles + $this->total_workdays;

        $total1 = $total * (($this->utility/100)+1);
        $total2 = $total1 * (($this->letter/100)+1);
        $total3 = $total2 * (($this->rent/100)+1);
        $totalFinal =  $total3;

        return $totalFinal;

    }
}
