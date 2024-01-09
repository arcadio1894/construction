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
        'email',
        'area'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer')->withTrashed();

    }

    public function quotes()
    {
        return $this->hasMany('App\Quote', 'contact_id');
    }

    protected $dates = ['deleted_at'];
}
