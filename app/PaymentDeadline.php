<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentDeadline extends Model
{
    protected $fillable = [
        'description',
        'days'
    ];


}
