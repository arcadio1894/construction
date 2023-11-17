<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SuppliersExport implements WithMultipleSheets
{
    protected $data;
    protected $deletedData;

    public function __construct(array $data, array $deletedData)
    {
        $this->data = $data;
        $this->deletedData = $deletedData;
    }

    public function sheets(): array
    {
        $sheets = [];


        $sheets[] = new ReportSuppliersSheet($this->data);


        // Add sheet for deleted records
        $sheets[] = new DeletedSuppliersSheet($this->deletedData);

        return $sheets;
    }
}
