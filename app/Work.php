<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Work extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'timeline_id',
        'quote_id',
        'description_quote',
    ];

    public function timeline()
    {
        return $this->belongsTo('App\Timeline');
    }

    public function quote()
    {
        return $this->belongsTo('App\Quote');
    }

    public function phases()
    {
        return $this->hasMany('App\Phase');
    }

    public function getRowspanAttribute()
    {
        $tasks = Task::where('work_id', $this->id)->get();
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
