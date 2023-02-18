<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TotalHoursExcelMultipleSheet implements WithMultipleSheets
{
    use Exportable;
    public $arrayByWeek, $arrayByDates, $title;

    public function __construct(array $arrayByWeek, array $arrayByDates, $title)
    {
        $this->arrayByWeek = $arrayByWeek;
        $this->arrayByDates = $arrayByDates;
        $this->title = $title;
    }
    public function sheets(): array
    {
        return [
            new TotalHoursDetailExcelExport($this->arrayByDates,$this->title),
            new TotalHoursSummaryExcelExport($this->arrayByWeek,$this->title),
        ];
    }
}
