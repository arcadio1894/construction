<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proforma extends Model
{
    protected $appends = ['total_proforma', 'total_equipments'];

    protected $fillable = [
        'code',
        'description_quote',
        'date_quote',
        'date_validate',
        'delivery_time',
        'customer_id',
        'contact_id',
        'payment_deadline_id',
        'vb_proforma',
        'date_vb_proforma',
        'user_vb_proforma',
        'total',
        'state',
        'currency',
        'currency_compra',
        'currency_venta',
        'total_soles',
        'observations',
        'user_creator'
    ];

    protected $dates = ['date_quote', 'date_validate', 'date_vb_proforma'];

    public function getTotalEquipmentsAttribute()
    {
        $totalFinal2 = 0;
        $equipos = EquipmentProforma::where('proforma_id', $this->id)->get();
        foreach ( $equipos as $equipment )
        {
            $totalFinal2 = $totalFinal2 + $equipment->total_equipment;
        }

        return $totalFinal2;
    }

    public function getTotalProformaAttribute()
    {
        $equipments = $this->equipments;

        $totalFinal = 0;

        foreach ( $equipments as $equipment )
        {
            $totalFinal+=$equipment->total_equipment_utility;
        }

        return $totalFinal;
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function contact()
    {
        return $this->belongsTo('App\ContactName', 'contact_id');
    }

    public function user_vb()
    {
        return $this->belongsTo('App\User', 'user_vb_proforma', 'id');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'user_creator', 'id');

    }

    public function equipments()
    {
        return $this->hasMany('App\EquipmentProforma')->orderBy('description','asc');
    }

    public function deadline()
    {
        return $this->belongsTo('App\PaymentDeadline', 'payment_deadline_id');
    }


}
