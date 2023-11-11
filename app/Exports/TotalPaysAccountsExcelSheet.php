<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class TotalPaysAccountsExcelSheet implements FromView, WithTitle
{
    use Exportable;

    protected $week;

    public function __construct($week)
    {
        $this->week = $week;
    }

    public function view(): View
    {
        return view('exports.excelTotalPaysAccountsSheet', ['week' => $this->week]);
    }

    public function title(): string
    {
        return 'Semana ' . $this->week['week'];
    }
}
