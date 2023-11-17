<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportCustomerContactExport implements WithMultipleSheets
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

        
        $sheets[] = new ReportCustomerContactSheet($this->data);
        

        // Add sheet for deleted records
        $sheets[] = new DeletedReportCustomerContactSheet($this->deletedData);

        return $sheets;
    }
}