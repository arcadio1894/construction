<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class AmountReport implements FromView
{
    public $materials;
    public $total_amount_dollars;
    public $total_amount_soles;
    public $total_quantity_dollars;
    public $total_quantity_soles;

    public function __construct(array $materials, $total_amount_dollars, $total_amount_soles, $total_quantity_dollars,$total_quantity_soles)
    {
        $this->materials = $materials;
        $this->total_amount_dollars = $total_amount_dollars;
        $this->total_amount_soles = $total_amount_soles;
        $this->total_quantity_dollars = $total_quantity_dollars;
        $this->total_quantity_soles = $total_quantity_soles;
    }

    public function view(): View
    {
        return view('exports.excelAmount', [
            'materials'=>$this->materials,
            'total_amount_dollars' => $this->total_amount_dollars,
            'total_amount_soles' => $this->total_amount_soles,
            'total_quantity_dollars' => $this->total_quantity_dollars,
            'total_quantity_soles' => $this->total_quantity_soles
        ]);
    }
}
