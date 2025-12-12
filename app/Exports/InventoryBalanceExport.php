<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class InventoryBalanceExport implements FromArray, WithHeadings, WithColumnWidths, WithEvents
{
    protected $rows;
    protected $title;

    public function __construct(array $rows, string $title)
    {
        $this->rows  = $rows;
        $this->title = $title;
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

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 60,
            'C' => 30,
            'D' => 20,
            'E' => 60,
            'F' => 35,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                // Insertar una fila al inicio
                $event->sheet->insertNewRowBefore(1, 2);

                // Combinar celdas del título
                $event->sheet->mergeCells('A1:F1');

                // Setear texto del título
                $event->sheet->setCellValue('A1', $this->title);

                // Estilos del título
                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical'   => 'center',
                    ],
                ]);
            },
        ];
    }
}
