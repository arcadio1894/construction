<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    protected $appends = ['subtotal_utility', 'subtotal_letter', 'subtotal_rent', 'subtotal_rent_pdf', 'subtotal_utility_edit', 'subtotal_letter_edit', 'subtotal_rent_edit', 'total_quote', 'total_equipments'];

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
        'state_active',
        'contact_id',
        'payment_deadline_id',
        'reason_separate',
        'vb_finances',
        'date_vb_finances',
        'vb_operations',
        'date_vb_operations'
    ];

    public function getTotalEquipmentsAttribute()
    {
        $totalFinal2 = 0;
        $equipos = Equipment::where('quote_id', $this->id)->get();
        foreach ( $equipos as $equipment )
        {
            $totalFinal2 = $totalFinal2 + $equipment->total;
        }

        return $totalFinal2;
    }
    
    public function getTotalQuoteAttribute()
    {
        $nuevo = true;
        $equipos = Equipment::where('quote_id', $this->id)->get();
        foreach ( $equipos as $equipment )
        {
            if ( $equipment->utility == 0 && $equipment->letter == 0 && $equipment->rent == 0 )
            {
                $nuevo = false;
            } else {
                $nuevo = true;
            }
        }

        $totalFinal = 0;
        if ( !$nuevo )
        {
            if ( $this->total_soles != 0 )
            {
                $subtotal1 = $this->total_soles * (($this->utility/100)+1);
                $subtotal2 = $subtotal1 * (($this->letter/100)+1);
                $subtotal3 = $subtotal2 * (($this->rent/100)+1);
                $totalFinal = $subtotal3;
            } else {
                $subtotal1 = $this->total * (($this->utility/100)+1);
                $subtotal2 = $subtotal1 * (($this->letter/100)+1);
                $subtotal3 = $subtotal2 * (($this->rent/100)+1);
                $totalFinal =  $subtotal3;
            }
        } else {
            if ( $this->total_soles != 0 )
            {
                $totalFinal = $this->total_soles;
            } else {
                $totalFinal = $this->total;
            }
        }

        return $totalFinal;
    }

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

    public function getSubtotalRentPdfAttribute()
    {
        if ( $this->total_soles != 0 )
        {
            $subtotal1 = $this->total_soles * (($this->utility/100)+1);
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

    public function contact()
    {
        return $this->belongsTo('App\ContactName', 'contact_id');
    }

    public function equipments()
    {
        return $this->hasMany('App\Equipment')->orderBy('description','asc');
    }

    public function users()
    {
        return $this->hasMany('App\QuoteUser');
    }

    public function deadline()
    {
        return $this->belongsTo('App\PaymentDeadline', 'payment_deadline_id');
    }

    public function outputs()
    {
        return $this->hasMany('App\Output', 'execution_order', 'order_execution');
    }

}
