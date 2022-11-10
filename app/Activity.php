<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'quote_id',
        'timeline_id',
        'description_quote',
        'activity',
        'progress'
    ];

    protected $dates = ['deleted_at'];

    public function quote()
    {
        return $this->belongsTo('App\Quote');
    }

    public function timeline()
    {
        return $this->belongsTo('App\Timeline');
    }

    public function activity_workers()
    {
        return $this->hasMany('App\ActivityWorker');
    }


}
