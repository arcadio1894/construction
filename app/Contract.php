<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'date_start',
        'date_fin',
        'file',
        'enable',
        'worker_id'
    ];

    protected $dates = ['deleted_at', 'date_start', 'date_fin'];

    public function worker()
    {
        return $this->belongsTo('App\Worker');
    }
}
