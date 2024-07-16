<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class ReportHaberesExport implements FromView
{
    use Exportable;
    public $haberes, $title, $subtitle;

    public function __construct(array $haberes, $title, $subtitle)
    {
        $this->haberes = $haberes;
        $this->title = $title;
        $this->subtitle = $subtitle;
    }

    public function view(): View
    {
        return view('exports.excelHaberes', ['haberes'=>$this->haberes,'title'=>$this->title, 'subtitle'=>$this->subtitle]);
    }
}
