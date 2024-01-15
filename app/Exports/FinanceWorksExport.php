<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class FinanceWorksExport implements FromView
{
    use Exportable;
    public $financeWorks, $dates;

    public function __construct(array $financeWorks, $dates)
    {
        $this->financeWorks = $financeWorks;
        $this->dates = $dates;
    }

    public function view(): View
    {
        return view('exports.excelFinanceWorks', ['financeWorks'=>$this->financeWorks,'dates'=>$this->dates]);
    }
}
