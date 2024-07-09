<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReasonTransfer extends Model
{
    use SoftDeletes;

    protected $fillable = ['description'];

    public function guides()
    {
        return $this->hasMany('App\ReferralGuide');
    }

    protected $dates = ['deleted_at'];
}
