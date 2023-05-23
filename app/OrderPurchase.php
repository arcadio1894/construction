<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPurchase extends Model
{
    use SoftDeletes;

    protected $appends = ['status'];

    public $fillable = [
        'code',
        'supplier_id',
        'date_arrival',
        'date_order',
        'approved_by',
        'payment_condition',
        'currency_order',
        'currency_compra',
        'currency_venta',
        'igv',
        'total',
        'observation',
        'type',
        'quote_supplier',
        'regularize',
        'payment_deadline_id',
        'status_order'
    ];

    protected $dates = ['deleted_at'];

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    public function approved_user()
    {
        return $this->belongsTo('App\User', 'approved_by');
    }

    public function details()
    {
        return $this->hasMany('App\OrderPurchaseDetail');
    }

    public function deadline()
    {
        return $this->belongsTo('App\PaymentDeadline', 'payment_deadline_id');
    }

    public function getStatusAttribute()
    {
        $entry = Entry::where('purchase_order', $this->code)
            ->get();

        $order_purchase = OrderPurchase::where('code', $this->code)->first();

        if ( count($entry) > 0 )
        {
            $details = OrderPurchaseDetail::where('order_purchase_id', $order_purchase->id)->get();

            if (isset($details))
            {
                foreach ($details as $detail)
                {
                    $material = $detail->material_id;
                    // TODO: obtener las entradas de esa orden y material
                    $cant_material = 0;
                    foreach ( $entry as $entrada )
                    {
                        $entry_details_sum = DetailEntry::where('entry_id', $entrada->id)
                            ->where('material_id', $material)->sum('entered_quantity');
                        $cant_material += $entry_details_sum;
                    }


                    if ($cant_material < $detail->quantity)
                    {
                        // TODO: Esto significa que esta incompleta
                        return 0;
                    }
                }
                // TODO: Esto significa que esta completa
                return 1;
            }
            // TODO: Esto significa que esta por ingresar
            return 2;
        }
        // TODO: Esto significa que esta por ingresar
        return 2;
        //$number = ($this->entered_quantity * $this->unit_price)/1.18;
        //return number_format($subtotal, 2, '.', '');
    }

}
