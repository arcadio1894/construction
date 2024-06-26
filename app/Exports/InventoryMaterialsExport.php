<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class InventoryMaterialsExport implements FromView, WithTitle
{
    public $materials;
    public $title;

    public function __construct(array $materials, $title)
    {
        $this->materials = $materials;
        $this->title = $title;
    }

    public function view(): View
    {
        return view('exports.excelInventory', ['materials'=>$this->materials, 'title'=>$this->title]);
    }

    public function title(): string
    {
        return 'Inventario';
    }
}
