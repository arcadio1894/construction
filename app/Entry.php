<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $fillable = [
        'referral_guide',
        'purchase_order',
        'invoice',
        'entry_type'
    ];

    public function details()
    {
        return $this->hasMany('App\DetailEntry');
    }

    protected $dates = ['deleted_at'];
}
