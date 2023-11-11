<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TotalPaysAccountsExcelMultipleSheet implements WithMultipleSheets
{
    use Exportable;
    public $weeks;

    public function __construct(array $weeks)
    {
        $this->weeks  = $weeks;
    }
    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->weeks as $week) {
            $sheets[] = new TotalPaysAccountsExcelSheet($week);
        }

        return $sheets;
    }
}
