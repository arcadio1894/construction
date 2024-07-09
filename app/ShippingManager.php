<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingManager extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id'];

    public function guides()
    {
        return $this->hasMany('App\ReferralGuide');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    protected $dates = ['deleted_at'];
}
