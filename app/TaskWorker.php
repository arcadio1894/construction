<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskWorker extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'task_id',
        'worker_id',
        'hours_plan',
        'hours_real',
    ];

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    public function worker()
    {
        return $this->belongsTo('App\Worker');
    }

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
