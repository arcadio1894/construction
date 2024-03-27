<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResumenQuote extends Model
{
    protected $fillable = [
        'quote_id',
        'code',
        'description_quote',
        'date_quote',
        'customer_id',
        'customer',
        'contact_id',
        'contact',
        'total_sin_igv',
        'total_con_igv',
        'total_utilidad_sin_igv',
        'total_utilidad_con_igv',
        'path_pdf'
    ];

    protected $dates = ['date_quote'];

    public function quote()
    {
        return $this->belongsTo('App\Quote');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function contact()
    {
        return $this->belongsTo('App\ContactName', 'contact_id');
    }

    public function details()
    {
        return $this->hasMany('App\ResumenEquipment', 'resumen_quote_id');
    }
}
