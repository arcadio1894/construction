<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class TotalHoursSummaryExcelExport implements FromView, WithTitle
{
    use Exportable;
    public $arrayByWeek, $title;

    public function __construct(array $arrayByWeek, $title)
    {
        $this->arrayByWeek = $arrayByWeek;
        $this->title = $title;
    }

    public function view(): View
    {
        return view('exports.excelTotalHoursSummaryReport', [
            'arrayByWeek'=>$this->arrayByWeek,
            'title'=>$this->title
        ]);
    }

    public function title(): string
    {
        return 'Total Horas Semanal';
    }
}
