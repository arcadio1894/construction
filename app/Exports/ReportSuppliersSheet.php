<?php

namespace App\Exports;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportSuppliersSheet implements FromView, WithTitle

{
    use Exportable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {   //dump($this->data);
        //dd($this->data);

        $fecha = Carbon::now()->format('d-m-Y');
        return view('exports.excelReportSupplierAccount', ['data' => $this->data, 'date' => $fecha]);
    }

    public function title(): string
    {
        return 'Activos';
    }
}
