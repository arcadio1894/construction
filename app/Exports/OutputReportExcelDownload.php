<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class OutputReportExcelDownload implements FromView
{
    use Exportable;
    public $outputs, $dates;

    public function __construct(array $array, $dates)
    {
        $this->outputs = $array;
        $this->dates = $dates;
    }

    public function view(): View
    {
        return view('exports.excelExportOutputs', ['outputs'=>$this->outputs,'dates'=>$this->dates]);
    }
}
