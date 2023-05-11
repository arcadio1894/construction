<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExpensesReportExcelExport implements FromView
{
    use Exportable;
    public $expenses, $dates;

    public function __construct(array $expenses, $dates)
    {
        $this->expenses = $expenses;
        $this->dates = $dates;
    }

    public function view(): View
    {
        return view('exports.excelExpensesReport', ['expenses'=>$this->expenses,'dates'=>$this->dates]);
    }
}
