<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class QuotesReportExcelExport implements FromView
{
    use Exportable;
    public $quotes, $dates;

    public function __construct(array $quotes, $dates)
    {
        $this->quotes = $quotes;
        $this->dates = $dates;
    }

    public function view(): View
    {
        return view('exports.excelQuotesReport', ['quotes'=>$this->quotes,'dates'=>$this->dates]);
    }
}
