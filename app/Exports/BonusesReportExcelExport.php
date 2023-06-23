<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BonusesReportExcelExport implements FromView
{
    use Exportable;
    public $bonuses, $dates;

    public function __construct(array $bonuses, $dates)
    {
        $this->bonuses = $bonuses;
        $this->dates = $dates;
    }

    public function view(): View
    {
        return view('exports.excelBonusesReport', ['bonuses'=>$this->bonuses,'dates'=>$this->dates]);
    }
}
