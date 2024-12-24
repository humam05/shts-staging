<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MonthlyExport implements FromCollection, WithHeadings, WithMapping
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
            'Pembayaran',
            'Sisa Hutang',
            'Last Transaction Bulan',
            'Last Transaction Year',
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
            $transaction->pembayaran,
            $transaction->sisa_hutang,
            $transaction->last_transaction_bulan,
            $transaction->last_transaction_year,
        ];
    }
}
