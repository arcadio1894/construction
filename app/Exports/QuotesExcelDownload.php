<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class QuotesExcelDownload implements FromView
{
    use Exportable;
    public $quotes, $dates;

    public function __construct(array $quotes, $dates)
    {
        $this->quotes = $quotes;
        $this->dates = $dates;
    }

    public function view(): View
    {
        return view('exports.excelQuotesDownload', ['quotes'=>$this->quotes,'dates'=>$this->dates]);
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
