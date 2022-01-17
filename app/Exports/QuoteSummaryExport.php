<?php

namespace App\Exports;

use App\Quote;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class QuoteSummaryExport implements FromView
{
    public $quotes;

    public function __construct(array $quotes)
    {
        $this->quotes = $quotes;
    }

    public function view(): View
    {
        return view('exports.excelQuoteSummary', ['quotes'=>$this->quotes]);
    }
}
