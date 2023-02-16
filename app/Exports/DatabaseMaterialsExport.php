<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DatabaseMaterialsExport implements FromView
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
        return view('exports.excelDataBase', ['materials'=>$this->materials, 'title'=>$this->title]);
    }
}
