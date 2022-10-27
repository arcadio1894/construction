<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StockMaterialsExcel implements FromView
{
    use Exportable;
    public $materials;

    public function __construct(array $materials)
    {
        $this->materials = $materials;
    }

    public function view(): View
    {
        return view('exports.excelStockMaterials', ['materials'=>$this->materials]);
    }
}
