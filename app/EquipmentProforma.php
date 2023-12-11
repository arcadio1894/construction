<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipmentProforma extends Model
{
    protected $table = 'equipment_proformas';

    protected $appends = ['subtotal_rent', 'subtotal_utility', 'subtotal_percentage'];

    protected $fillable = [
        'proforma_id',
        'default_equipment_id',
        'description',
        'detail',
        'quantity',
        'total',
        'utility',
        'letter',
        'rent'
    ];

    public function getSubtotalUtilityAttribute()
    {
        $subtotal1 = $this->total * (($this->utility/100)+1);
        return $subtotal1;

    }

    public function getSubtotalPercentageAttribute()
    {
        $subtotal1 = $this->total * (($this->utility/100)+1);
        $subtotal2 = $subtotal1 * (($this->letter/100)+1);
        $subtotal3 = $subtotal2 * (($this->rent/100)+1);
        return $subtotal3;

    }

    public function getSubtotalRentAttribute()
    {
        $subtotal1 = $this->total * (($this->utility/100)+1);
        $subtotal2 = $subtotal1 * (($this->letter/100)+1);
        $subtotal3 = $subtotal2 * (($this->rent/100)+1);
        return $subtotal3;
    }

    public function proforma()
    {
        return $this->belongsTo('App\Proforma');
    }

    public function materials()
    {
        return $this->hasMany('App\EquipmentProformaMaterial');
    }

    public function consumables()
    {
        return $this->hasMany('App\EquipmentProformaConsumable');
    }

    public function workforces()
    {
        return $this->hasMany('App\EquipmentProformaWorkforces');
    }

    public function turnstiles()
    {
        return $this->hasMany('App\EquipmentProformaTurnstiles');
    }

    public function workdays()
    {
        return $this->hasMany('App\EquipmentProformaWorkdays');
    }

    public function getTotalMaterialsAttribute()
    {
        $total = 0;
        foreach ( $this->materials as $material )
        {
            $total += $material->total_price;
        }

        //return $total*$this->quantity;
        return $total;
    }

    public function getTotalConsumablesAttribute()
    {
        $total = 0;
        foreach ( $this->consumables as $consumable )
        {
            $total += $consumable->total_price;
        }

        //return $total*$this->quantity;
        return $total;
    }

    public function getTotalWorkforcesAttribute()
    {
        $total = 0;
        foreach ( $this->workforces as $workforce )
        {
            $total += $workforce->total_price;
        }

        //return $total*$this->quantity;
        return $total;
    }

    public function getTotalTurnstilesAttribute()
    {
        $total = 0;
        foreach ( $this->turnstiles as $turnstile )
        {
            $total += $turnstile->total_price;
        }

        //return $total*$this->quantity;
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
        $total = $this->total_materials + $this->total_consumables + $this->total_workforces + $this->total_turnstiles + $this->total_workdays;

        return $total;

    }

    public function getTotalEquipmentUtilityAttribute()
    {
        $total = $this->total_materials + $this->total_consumables + $this->total_workforces + $this->total_turnstiles + $this->total_workdays;

        $total1 = $total * (($this->utility/100)+1);
        $total2 = $total1 * (($this->letter/100)+1);
        $total3 = $total2 * (($this->rent/100)+1);
        $totalFinal =  $total3;

        return $totalFinal;

    }
}
