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
        'progress',
        'phase',
        'performer',
        'assign_status',
        'parent_activity'
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

    public function performer_worker()
    {
        return $this->belongsTo('App\Worker', 'performer');

    }

    public function activity_parent()
    {
        return $this->belongsTo('App\Activity', 'parent_activity');

    }

    public function activity_workers()
    {
        return $this->hasMany('App\ActivityWorker');
    }


}
