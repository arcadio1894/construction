<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class TotalHoursDetailExcelExport implements FromView, WithTitle
{
    use Exportable;
    public $arrayByDates, $title;

    public function __construct(array $arrayByDates, $title)
    {
        $this->arrayByDates = $arrayByDates;
        $this->title = $title;
    }

    public function view(): View
    {
        return view('exports.excelTotalHoursDetailReport', [
            'arrayByDates'=>$this->arrayByDates,
            'title'=>$this->title
        ]);
    }

    public function title(): string
    {
        return 'Total Horas Detalle';
    }
}
