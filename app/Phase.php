<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Phase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'timeline_id',
        'work_id',
        'description',
    ];

    public function timeline()
    {
        return $this->belongsTo('App\Timeline');
    }

    public function work()
    {
        return $this->belongsTo('App\Work');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    public function getRowspanAttribute()
    {
        $tasks = Task::where('phase_id', $this->id)->get();
        $quantity = 0;

        foreach ( $tasks as $task )
        {
            $workers = TaskWorker::where('task_id', $task->id)->get();
            $quantity = $quantity + count($workers);
        }

        return $quantity;
    }

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
