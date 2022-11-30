<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class ReportOrderPurchaseExport implements FromView
{
    use Exportable;
    public $orders, $dates;

    public function __construct(array $orders, $dates)
    {
        $this->orders = $orders;
        $this->dates = $dates;
    }

    public function view(): View
    {
        return view('exports.excelOrderPurchase', ['orders'=>$this->orders,'dates'=>$this->dates]);
    }
}
