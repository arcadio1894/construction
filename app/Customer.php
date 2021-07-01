<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'business_name','RUC', 'code','address', 'location'
    ];

    // TODO: Las relaciones
    public function quotes()
    {
        return $this->hasMany('App\Quote');
    }

    public function contactNames()
    {
        return $this->hasMany('App\ContactName');
    }

    /* En la cotizacion
    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }*/

    protected $dates = ['deleted_at'];
}
