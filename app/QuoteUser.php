<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteUser extends Model
{
    protected $fillable = [
        'quote_id',
        'user_id',
    ];

    public function quote(){
        return $this->belongsTo('App\Quote');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
