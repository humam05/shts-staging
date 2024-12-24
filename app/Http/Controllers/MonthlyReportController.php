<?php

namespace App\Http\Controllers;

use App\Exports\MonthlyExport;
use App\Exports\MonthlyReportExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class MonthlyReportController extends Controller
{
    public function index(Request $request)
    {


        // 1. Set Default Year and Month (1 month prior to current date)
        $now = Carbon::now();
        $defaultDate = $now->copy()->subMonth(); // Clone to prevent mutation
        $defaultYear = $defaultDate->year;
        $defaultMonth = $defaultDate->month;

        // 2. Retrieve Filters from Request
        $year = $request->input('tahun', $defaultYear);
        $month = $request->input('bulan', $defaultMonth);
        $unitFilter = $request->input('unit');
        $transaksiTerakhirFilter = $request->input('transaksi_akhir');
        $statusLunasFilter = $request->input('status_lunas');
        $isExport = $request->input('export') === 'true';

        // 3. Build the Base Query
        $query = DB::table('t_user')
            ->leftJoin('transactions', function ($join) use ($year, $month) {
                $join->on('t_user.code', '=', 'transactions.code')
                    ->where('transactions.bulan', '=', $month)
                    ->where('transactions.year', '=', $year);
            })
            ->leftJoin('lampiran', 't_user.code', '=', 'lampiran.code')
            ->leftJoin('transactions as last_transaction', function ($join) {
                $join->on('t_user.code', '=', 'last_transaction.code')
                    ->whereRaw('last_transaction.id = (SELECT MAX(id) FROM transactions WHERE code = t_user.code)');
            })
            ->select(
                't_user.code',
                't_user.nama',
                'lampiran.no_spp',
                'lampiran.tanggal_spp',
                'lampiran.status_karyawan',
                'lampiran.unit',
                'lampiran.hutang',
                DB::raw("SUM(transactions.pencicilan_rutin + transactions.pencicilan_bertahap) AS pembayaran"),
                DB::raw("(lampiran.hutang - COALESCE((
                SELECT SUM(t.pencicilan_rutin + t.pencicilan_bertahap)
                FROM transactions t
                WHERE t.code = t_user.code
                GROUP BY t.code
            ), 0)) AS sisa_hutang"),
                'last_transaction.id as last_transaction_id',
                'last_transaction.bulan as last_transaction_bulan',
                'last_transaction.year as last_transaction_year',
                'last_transaction.pencicilan_rutin as last_pencicilan_rutin',
                'last_transaction.pencicilan_bertahap as last_pencicilan_bertahap'
            )
            ->groupBy(
                't_user.code',
                't_user.nama',
                'lampiran.no_spp',
                'lampiran.tanggal_spp',
                'lampiran.status_karyawan',
                'lampiran.unit',
                'lampiran.hutang',
                'last_transaction.id',
                'last_transaction.bulan',
                'last_transaction.year',
                'last_transaction.pencicilan_rutin',
                'last_transaction.pencicilan_bertahap'
            );

        // 4. Apply Status Lunas Filter if Present
        if ($request->filled('status_lunas') && in_array($statusLunasFilter, ['Lunas', 'Belum Lunas'])) {
            $query->selectRaw("
            CASE 
                WHEN (lampiran.hutang - COALESCE((
                    SELECT SUM(t.pencicilan_rutin + t.pencicilan_bertahap)
                    FROM transactions t
                    WHERE t.code = t_user.code
                    GROUP BY t.code
                ), 0)) = 0 
                THEN 'Lunas' 
                ELSE 'Belum Lunas' 
            END AS status_lunas
        ")
                ->having('status_lunas', '=', $statusLunasFilter);
        }

        // 5. Apply Unit Filter if Present
        if (!empty($unitFilter)) {
            $query->where('lampiran.unit', '=', $unitFilter);
        }

        // 6. Apply Transaksi Terakhir Filter if Present
        if (!empty($transaksiTerakhirFilter) && preg_match('/^\d{4}-\d{2}$/', $transaksiTerakhirFilter)) {
            list($filterYear, $filterMonth) = explode('-', $transaksiTerakhirFilter);
            $query->where('last_transaction.year', '=', $filterYear)
                ->where('last_transaction.bulan', '=', $filterMonth);
        }

        // 7. Execute the Query
        $transactions = $query->get();

        // 8. Handle Export to Excel if Requested
        if ($isExport) {
            return Excel::download(new MonthlyExport($transactions), 'monthly_report.xlsx');
        }

        // 9. Return the View with Data
        return view('admin.panel.monthlyreport', compact('transactions', 'year', 'month'));
    }
}
