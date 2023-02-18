<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class HoursDiaryExcelExport implements FromView, WithTitle
{
    use Exportable;
    public $arrayDays, $arrayHeaders, $arrayAssistances, $dates;

    public function __construct(array $arrayDays, array $arrayHeaders, array $arrayAssistances, $dates)
    {
        $this->arrayDays = $arrayDays;
        $this->arrayHeaders = $arrayHeaders;
        $this->arrayAssistances = $arrayAssistances;
        $this->dates = $dates;
    }

    public function view(): View
    {
        return view('exports.excelHourDiaryReport', [
            'arrayDays'=>$this->arrayDays,
            'arrayHeaders'=>$this->arrayHeaders,
            'arrayAssistances'=>$this->arrayAssistances,
            'dates'=>$this->dates
        ]);
    }

    public function title(): string
    {
        return 'Horas Diarias';
    }
}
