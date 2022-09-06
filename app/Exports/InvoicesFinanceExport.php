<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class InvoicesFinanceExport implements FromView
{
    use Exportable;
    public $invoices, $dates;

    public function __construct(array $invoices, $dates)
    {
        $this->invoices = $invoices;
        $this->dates = $dates;
    }

    public function view(): View
    {
        return view('exports.excelInvoiceFinance', ['invoices'=>$this->invoices,'dates'=>$this->dates]);
    }
}
