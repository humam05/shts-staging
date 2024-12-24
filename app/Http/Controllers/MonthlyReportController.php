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
        // Menentukan tanggal bulan dan tahun default (1 bulan sebelumnya)
        $now = Carbon::now();
        $defaultYear = $now->subMonth()->year; // Tahun 1 bulan sebelumnya
        $defaultMonth = $now->subMonth()->month; // Bulan 1 bulan sebelumnya
        $unitFilter = $request->get('unit');
        $transaksiTerakhirFilter = $request->get('transaksi_akhir');


        // Menentukan tahun dan bulan berdasarkan request, jika tidak ada, gunakan default
        $year = $request->get('tahun', $defaultYear);
        $month = $request->get('bulan', $defaultMonth);

        // Menjalankan query berdasarkan tahun dan bulan yang dipilih
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
            );

        // Menambahkan filter tambahan untuk status lunas jika ada
        if ($request->has('status_lunas') && in_array($request->status_lunas, ['Lunas', 'Belum Lunas'])) {
            $statusFilter = $request->status_lunas;
            $query->addSelect(DB::raw(" 
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
        "))
                ->having('status_lunas', '=', $statusFilter);
        }

        // Filter berdasarkan unit
        if ($unitFilter) {
            $query->where('lampiran.unit', '=', $unitFilter);
        }

        if ($transaksiTerakhirFilter) {
            // Mengubah format 'YYYY-MM' menjadi tahun dan bulan
            list($filterYear, $filterMonth) = explode('-', $transaksiTerakhirFilter);

            $query->where('last_transaction.year', '=', $filterYear)
                ->where('last_transaction.bulan', '=', $filterMonth);
        }

        // Mendapatkan data transaksi
        $transactions = $query
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
            )
            ->get();

        if ($request->has('export') && $request->export == 'true') {
            return Excel::download(new MonthlyExport($transactions), 'monthly_report.xlsx');
        }

        // Mengirim data ke view
        return view('admin.panel.monthlyreport', compact('transactions', 'year', 'month'));
    }
}
