<?php

namespace App\Exports;

use App\Timeline;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class TimelinesExports implements FromView
{
    use Exportable;

    public $tasks;
    public $title;

    public function __construct($title, array $tasks)
    {
        $this->tasks = $tasks;
        $this->title = $title;
    }

    public function view(): View
    {
        return view('exports.timelineExcel', ['tasks'=>$this->tasks, 'title'=>$this->title]);
    }
}
