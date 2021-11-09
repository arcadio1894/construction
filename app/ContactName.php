<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactName extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'customer_id',
        'phone',
        'email'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer')->withTrashed();

    }

    protected $dates = ['deleted_at'];
}
