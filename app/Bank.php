<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = [
        'name',
        'short_name',
        'image'
    ];

    public function finance_works()
    {
        return $this->hasMany('App\FinanceWork');
    }
}
