<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class SummaryAssistanceReportExcel implements FromView, WithTitle
{
    use Exportable;
    public $arrayAssistances, $arraySummary, $dates;

    public function __construct(array $arrayAssistances, array $arraySummary, $dates)
    {
        $this->arrayAssistances = $arrayAssistances;
        $this->arraySummary = $arraySummary;
        $this->dates = $dates;
    }

    public function view(): View
    {
        return view('exports.excelAssistanceSummaryReport', [
            'arrayAssistances'=>$this->arrayAssistances,
            'arraySummary'=>$this->arraySummary,
            'dates'=>$this->dates
        ]);
    }

    public function title(): string
    {
        return 'Resumen';
    }
}
