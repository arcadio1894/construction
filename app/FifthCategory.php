<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FifthCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'date',
        'amount',
        'worker_id',
        'year',
        'total_amount',
        'month'
    ];

    protected $dates = ['deleted_at', 'date'];

    public function worker()
    {
        return $this->belongsTo('App\Worker');
    }

}
