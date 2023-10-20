<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermitHour extends Model
{
    use SoftDeletes;
    protected $table = 'permits_hours';
    protected $fillable = [
        'reason',
        'date_start',
        'hour',
        'worker_id'
    ];
    protected $dates = ['deleted_at', 'date_start'];

    public function worker()
    {
        return $this->belongsTo('App\Worker');
    }
}
