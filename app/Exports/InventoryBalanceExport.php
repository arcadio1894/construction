<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InventoryBalanceExport implements FromArray, WithHeadings, WithColumnWidths
{
    protected $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // Código
            'B' => 60, // Material
            'C' => 30, // Stock Sistema (Actualizado)
            'D' => 20, // Stock Físico
            'E' => 60, // Ubicaciones
            'F' => 35, // Acción realizada
        ];
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'Código',
            'Material',
            'Stock Sistema (actualizado)',
            'Stock Físico',
            'Ubicaciones',
            'Acción realizada',
        ];
    }
}
