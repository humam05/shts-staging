<?php

namespace App\Imports;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        DB::table('transactions')->insert([
            'code' => $row['code'],
            'bulan' => $row['bulan'],
            'year' => $row['tahun'],
            'pencicilan_rutin' => $row['rutin'],
            'pencicilan_bertahap' => $row['bertahap'],
        ]);
    }
}
