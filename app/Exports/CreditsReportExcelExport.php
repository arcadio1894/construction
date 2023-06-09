<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CreditsReportExcelExport implements FromView
{
    use Exportable;
    public $credits, $dates;

    public function __construct(array $credits, $dates)
    {
        $this->credits = $credits;
        $this->dates = $dates;
    }

    public function view(): View
    {
        return view('exports.excelCreditsReport', ['credits'=>$this->credits,'dates'=>$this->dates]);
    }
}
