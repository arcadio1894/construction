<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class OutputsByQuoteExcelExport implements FromView
{
    use Exportable;
    public $outputs, $dates;

    public function __construct(array $outputs, $dates)
    {
        $this->outputs = $outputs;
        $this->dates = $dates;
    }

    public function view(): View
    {
        return view('exports.reportOutputsByQuoteExcel', ['outputs'=>$this->outputs,'dates'=>$this->dates]);
    }
}
