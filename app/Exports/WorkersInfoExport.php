<?php

namespace App\Exports;

use App\Worker;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class WorkersInfoExport implements FromView
{
    use Exportable;
    public $workers;

    public function __construct(array $workers)
    {
        $this->workers = $workers;
    }

    public function view(): View
    {
        return view('exports.excelWorkersInfo', ['workers'=>$this->workers]);
    }
}
