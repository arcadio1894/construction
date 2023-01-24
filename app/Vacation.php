<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vacation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'date_start',
        'date_end',
        'worker_id'
    ];

    protected $dates = ['deleted_at', 'date_start', 'date_end'];

    public function worker()
    {
        return $this->belongsTo('App\Worker');
    }
}
