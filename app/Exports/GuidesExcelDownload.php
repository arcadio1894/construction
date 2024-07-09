<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class GuidesExcelDownload implements FromView
{
    use Exportable;
    public $guides, $dates;

    public function __construct(array $guides, $dates)
    {
        $this->guides = $guides;
        $this->dates = $dates;
    }

    public function view(): View
    {
        return view('exports.excelGuides', ['guides'=>$this->guides,'dates'=>$this->dates]);
    }
}
