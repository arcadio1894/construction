<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class EntriesReportExcelDownload implements FromView
{
    use Exportable;
    public $entries, $dates;

    public function __construct(array $array, $dates)
    {
        $this->entries = $array;
        $this->dates = $dates;
    }

    public function view(): View
    {
        return view('exports.excelExportEntries', ['entries'=>$this->entries,'dates'=>$this->dates]);
    }
}
