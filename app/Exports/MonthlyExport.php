<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class MonthlyExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $transactions;


    public function __construct($transactions)
    {
        $this->transactions = $transactions;
        
    }

    // Return the data for export
    public function collection()
    {
        return $this->transactions;
    }

    // Define headings for the export
    public function headings(): array
    {
        return [
            'Code',
            'Nama',          
            'Status Karyawan',
            'Unit',
            'Nilai SHT',
            'Pembayaran',
            'Sisa Hutang'
        ];
    }

    // Map the data for each row in the Excel file
    public function map($transaction): array
    {
        return [
            $transaction->code,
            $transaction->nama,
            $transaction->status_karyawan,
            $transaction->unit,
            $transaction->hutang,
            $transaction->pembayaran,
            $transaction->sisa_hutang
        ];
    }

    public function styles($sheet)
    {
        // Style for alternating rows
        $zebraStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'DCE6F1', // Light blue for zebra striping
                ],
            ],
        ];

        // Style for the header
        $headerStyle = [
            'font' => [
                'bold' => true, // Make header text bold
                'color' => [
                    'argb' => 'FFFFFF', // White text color
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '4F81BD', // Dark blue for header
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

        $rows = $sheet->getHighestRow();

        foreach (range(2, $rows) as $row) {
            if ($row % 2 == 0) {
                $sheet->getStyle("A$row:G$row")->applyFromArray($zebraStyle);
            }
        }

        $sheet->getStyle('E2:E' . $rows)->getNumberFormat()->setFormatCode('_(* #,##0.00_);_(* \(#,##0.00\);_(* "-"??_);_(@_)');
        $sheet->getStyle('F2:F' . $rows)->getNumberFormat()->setFormatCode('_(* #,##0.00_);_(* \(#,##0.00\);_(* "-"??_);_(@_)');       
        $sheet->getStyle('G2:G' . $rows)->getNumberFormat()->setFormatCode('_(* #,##0.00_);_(* \(#,##0.00\);_(* "-"??_);_(@_)');
        return [];
    }
}
