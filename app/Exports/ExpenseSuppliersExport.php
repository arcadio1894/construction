<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class ExpenseSuppliersExport implements FromView
{
    use Exportable;
    public $expenseSuppliers, $dates;

    public function __construct(array $expenseSuppliers, $dates)
    {
        $this->expenseSuppliers = $expenseSuppliers;
        $this->dates = $dates;
    }

    public function view(): View
    {
        return view('exports.excelExpenseSupplier', ['expenseSuppliers'=>$this->expenseSuppliers,'dates'=>$this->dates]);
    }
}
