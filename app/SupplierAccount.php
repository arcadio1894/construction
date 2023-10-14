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

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }
    public function bank()
    {
        return $this->belongsTo('App\Bank');
    }
}
