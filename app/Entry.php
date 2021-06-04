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
        'entry_type'
    ];

    public function details()
    {
        return $this->hasMany('App\DetailEntry');
    }

    protected $dates = ['deleted_at'];
}
