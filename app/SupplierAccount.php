<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierAccount extends Model
{
    protected $fillable = [
        'supplier_id',
        'number_account',
        'currency',
        'bank_id'
    ];

    public function worker()
    {
        return $this->hasMany('App\Supplier');
    }
    public function bank()
    {
        return $this->hasMany('App\Bank');
    }
}
