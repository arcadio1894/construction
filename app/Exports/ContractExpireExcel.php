<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class ContractExpireExcel implements FromView
{
    use Exportable;
    public $contracts;

    public function __construct(array $contracts)
    {
        $this->contracts = $contracts;
    }

    public function view(): View
    {
        return view('exports.excelContractsExpire', ['contracts'=>$this->contracts]);
    }
}
