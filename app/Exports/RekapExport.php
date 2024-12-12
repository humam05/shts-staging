<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RekapExport implements FromCollection, WithHeadings, WithStyles
{
    protected $filters;

    // Accept filters as an argument in the constructor
    public function __construct($filters)
    {
        $this->filters = $filters;
        // dd($filters);
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = DB::table('t_user')
            ->select(
                't_user.nama',
                't_user.code',
                'lampiran.tanggal_spp',
                'lampiran.no_spp',
                'lampiran.unit',
                'lampiran.status_karyawan',
                DB::raw('lampiran.hutang as hutang'),
                DB::raw('SUM(transactions.pencicilan_rutin + transactions.pencicilan_bertahap) as total_pembayaran'),
                DB::raw('lampiran.hutang - SUM(transactions.pencicilan_rutin + transactions.pencicilan_bertahap) as sisa_sht')
            )
            ->leftJoin('transactions', 't_user.code', '=', 'transactions.code')
            ->leftJoin('lampiran', 't_user.code', '=', 'lampiran.code');

        // Apply filters passed to the constructor
        if (isset($this->filters['status_lunas'])) {
            if ($this->filters['status_lunas'] == 'Belum Lunas') {
                $query->having(
                    DB::raw('lampiran.hutang - SUM(transactions.pencicilan_rutin + transactions.pencicilan_bertahap)'),
                    '>',
                    0
                );
            } else if ($this->filters['status_lunas'] == 'Lunas') {
                $query->having(
                    DB::raw('lampiran.hutang - SUM(transactions.pencicilan_rutin + transactions.pencicilan_bertahap)'),
                    '=',
                    0
                );
            }
        }

        // if (isset($this->filters['status'])) {
        //     $query->where('lampiran.status_karyawan', $this->filters['status']);
        // }

        if (isset($this->filters['status']) && $this->filters['status']) {
            $query->where('lampiran.status_karyawan', $this->filters['status']);
        }
        

        if (isset($this->filters['unit']) && $this->filters['unit']) {
            $query->where('lampiran.unit', $this->filters['unit']);
        }

        if (isset($this->filters['tahun'])) {
            $query->whereYear('lampiran.tanggal_spp', $this->filters['tahun']);
        }

        if (isset($this->filters['bulan'])) {
            $query->whereMonth('lampiran.tanggal_spp', $this->filters['bulan']);
        }

        if (isset($this->filters['sort_tanggal'])) {
            $query->orderBy('lampiran.tanggal_spp', $this->filters['sort_tanggal']);
        }

        return $query->groupBy(
            't_user.nama',
            't_user.code',
            'lampiran.tanggal_spp',
            'lampiran.no_spp',
            'lampiran.unit',
            'lampiran.status_karyawan',
            'lampiran.hutang'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Code',
            'Tanggal SPP',
            'No. SPP',
            'Unit',
            'Status Karyawan',
            'Hutang',
            'Total Pembayaran',
            'Sisa Hutang'
        ];
    }

     /**
    * Apply zebra striping to the rows
    */
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

        // Apply header style
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

        // Get the number of rows in the collection
        $rows = $sheet->getHighestRow();

        // Apply zebra striping to alternating rows
        foreach (range(2, $rows) as $row) {
            if ($row % 2 == 0) {
                $sheet->getStyle("A$row:I$row")->applyFromArray($zebraStyle);
            }
        }

        $sheet->getStyle('G2:G' . $rows)->getNumberFormat()->setFormatCode('_(* #,##0.00_);_(* \(#,##0.00\);_(* "-"??_);_(@_)');
        $sheet->getStyle('H2:H' . $rows)->getNumberFormat()->setFormatCode('_(* #,##0.00_);_(* \(#,##0.00\);_(* "-"??_);_(@_)');
        $sheet->getStyle('I2:I' . $rows)->getNumberFormat()->setFormatCode('_(* #,##0.00_);_(* \(#,##0.00\);_(* "-"??_);_(@_)');

        return [];
    }
}
