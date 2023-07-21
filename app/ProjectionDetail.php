<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectionDetail extends Model
{
    protected $fillable = [
        'projection_id',
        'worker_id',
        'salary'
    ];

    public function projection()
    {
        return $this->belongsTo('App\Projection');
    }

    public function worker()
    {
        return $this->belongsTo('App\Worker');
    }
}
