<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'timeline_id',
        'quote_id',
        'work_id',
        'phase_id',
        'performer_id',
        'assign_status',
        'parent_task_id',
        'activity',
        'progress',
    ];

    public function timeline()
    {
        return $this->belongsTo('App\Timeline');
    }

    public function work()
    {
        return $this->belongsTo('App\Work');
    }

    public function phase()
    {
        return $this->belongsTo('App\Phase');
    }

    public function quote()
    {
        return $this->belongsTo('App\Quote');
    }

    public function performer()
    {
        return $this->belongsTo('App\Worker', 'performer_id');
    }

    public function parent_task()
    {
        return $this->belongsTo('App\Task', 'parent_task_id');
    }

    public function task_workers()
    {
        return $this->hasMany('App\TaskWorker');
    }

    public function getRowspanAttribute()
    {
        $workers = TaskWorker::where('task_id', $this->id)->get();
        $quantity = 1;
        if ( !is_null($workers) )
        {
            $quantity = count($workers);
        }
        return $quantity;
    }

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
