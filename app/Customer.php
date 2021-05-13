<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['business_name','RUC', 'code','contact_name','adress','phone','location','email'];

    // TODO: Las relaciones
    public function quotes()
    {
        return $this->hasMany('App\Quote');
    }

    /* En la cotizacion
    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }*/
}
