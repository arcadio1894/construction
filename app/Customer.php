<?php

namespace App;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{

    use SoftDeletes, CascadeSoftDeletes;

    protected $fillable = [
        'business_name','RUC', 'code','address', 'location', 'special'
    ];

    protected $cascadeDeletes = ['contactNames'];

    // TODO: Las relaciones
    public function quotes()
    {
        return $this->hasMany('App\Quote');
    }

    public function contactNames()
    {
        return $this->hasMany('App\ContactName');
    }

    public function guides()
    {
        return $this->hasMany('App\ReferralGuide');
    }

    /* En la cotizacion
    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }*/

    protected $dates = ['deleted_at'];
}
