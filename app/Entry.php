<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'referral_guide',
        'purchase_order',
        'invoice',
        'supplier_id',
        'entry_type'
    ];

    public function details()
    {
        return $this->hasMany('App\DetailEntry');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    protected $dates = ['deleted_at'];
}
