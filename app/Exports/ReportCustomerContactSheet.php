<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

use Carbon\Carbon;

class ReportCustomerContactSheet implements FromView, WithTitle
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
        return view('exports.excelReportCustomerContact', ['data' => $this->data, 'date' => $fecha]);
    }

    public function title(): string
    {
        return 'Activos';
    }

}
/*
class ReportCustomerContactSheet implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $formattedData = [];

        foreach ($this->data as $customer) {

            // Customer header
            $formattedData[] = [
                'code' => 'code',
                'business_name' => 'business_name',
                'RUC' => 'RUC',
                'address' => 'address',
                'location' => 'location',
            ];

            // Customer data
            $formattedData[] = [
                'code' => $customer['code'],
                'business_name' => $customer['business_name'],
                'RUC' => $customer['RUC'],
                'address' => $customer['address'],
                'location' => $customer['location'],
            ];
            $formattedData[] = [''];

            // Contact Names header
            $formattedData[] = [
                'name' => 'name',
                'customer_id' => 'customer_id',
                'code' => 'code',
                'phone' => 'phone',
                'email' => 'email',
            ];

            // Contact Names data
            foreach ($customer['contacts'] as $contact) {
                $formattedData[] = [
                    'code' => $contact->code,
                    'name' => $contact->name,
                    'phone' => $contact->phone,
                    'email' => $contact->email,
                ];
            }
            $formattedData[] = [''];
            $formattedData[] = [''];
    }

        return collect($formattedData);
    }

    public function headings(): array
    {
        // The headings are dynamically added in the collection method
        return [];
    }


}
*/
