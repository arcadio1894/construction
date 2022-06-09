<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestPurchase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'quote_id',
        'urgency',
        'date'
    ];

    protected $dates = ['delete_at'];

    public function quote()
    {
        return $this->belongsTo('App\Quote');
    }

    public function details()
    {
        return $this->hasMany('App\RequestPurchaseDetail');
    }
}
