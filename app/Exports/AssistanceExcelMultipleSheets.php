<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AssistanceExcelMultipleSheets implements WithMultipleSheets
{
    use Exportable;
    public $arrayAssistances, $arraySummary, $dates;

    public function __construct(array $arrayAssistances, array $arraySummary, $dates)
    {
        $this->arrayAssistances = $arrayAssistances;
        $this->arraySummary = $arraySummary;
        $this->dates = $dates;
    }
    public function sheets(): array
    {
        return [
            new AssistanceReportExcelExport($this->arrayAssistances,$this->arraySummary,$this->dates),
            new SummaryAssistanceReportExcel($this->arrayAssistances,$this->arraySummary,$this->dates),
        ];
    }
}
