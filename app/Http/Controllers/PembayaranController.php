<?php

namespace App\Http\Controllers;

use App\Models\Lampiran;
use App\Models\Transaction;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PembayaranController extends Controller
{
    public function __construct()
    {
        // Pastikan hanya admin yang bisa mengakses
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        return view('admin.pembayaran');
    }

    public function autocomplete(Request $request)
    {
        $term = $request->get('term', '');

        // Eager load relasi lampiran agar tidak terjadi N+1 query
        $users = \App\Models\User::with(['lampiran', 'transactions'])
            ->where('role', 'user')
            ->where('kode_user', 'LIKE', '%' . $term . '%')
            ->limit(10)
            ->get();

        $results = [];
        foreach ($users as $user) {

            $totalPencicilan = $user->transactions->sum(function ($transaction) {
                $totalRutin = $transaction->pencicilan_rutin ?? 0;
                $totalBertahap = $transaction->pencicilan_bertahap ?? 0;
                return $totalRutin + $totalBertahap;
            });

            // Ambil hutang dari lampiran
            $hutang = $user->lampiran->hutang ?? 0;

            // Hitung sisa_sht
            $sisa_sht = $hutang - $totalPencicilan;

            // Tentukan status_lunas
            $status_lunas = $sisa_sht <= 0 ? 'Lunas' : 'Belum Lunas';

            $results[] = [
                'label' => $user->kode_user . ' - ' . $user->nama,
                'value' => $user->kode_user,
                'nama' => $user->nama,
                'no_spp' => $user->lampiran->no_spp ?? '', // Ambil dari tabel lampiran
                'status_karyawan' => $user->lampiran->status_karyawan ?? '', // Ambil dari tabel lampiran
                'tanggal_pensiun' => ($user->lampiran->bulan_pensiun ?? '') . '-' . ($user->lampiran->tahun_pensiun ?? ''),
                'nilai_pokok' => $hutang,
                'sisa_sht' => $sisa_sht,
                'status_lunas' => $status_lunas

            ];
        }

        return response()->json($results);
    }

    public function monitor(Request $request)
    {
        // Query untuk mendapatkan data transaksi per bulan
        $query = DB::table('transactions')
            ->select(
                'lampiran.status_karyawan',
                'transactions.code',
                't_user.nama',
                'lampiran.no_spp',
                'lampiran.unit',
                'lampiran.tanggal_spp',
                DB::raw('lampiran.hutang - COALESCE((SELECT SUM(t.pencicilan_rutin + t.pencicilan_bertahap)FROM transactions t WHERE t.code = transactions.code), 0) AS sisa_sht'),
                DB::raw('COALESCE((SELECT SUM(t.pencicilan_rutin + t.pencicilan_bertahap)FROM transactions t WHERE t.code = transactions.code), 0) AS total_dibayarkan'),
                DB::raw('SUM(CASE WHEN transactions.bulan = 1 THEN transactions.pencicilan_rutin + transactions.pencicilan_bertahap END) AS jan_value'),
                DB::raw('SUM(CASE WHEN transactions.bulan = 2 THEN transactions.pencicilan_rutin + transactions.pencicilan_bertahap END) AS feb_value'),
                DB::raw('SUM(CASE WHEN transactions.bulan = 3 THEN transactions.pencicilan_rutin + transactions.pencicilan_bertahap END) AS mar_value'),
                DB::raw('SUM(CASE WHEN transactions.bulan = 4 THEN transactions.pencicilan_rutin + transactions.pencicilan_bertahap END) AS apr_value'),
                DB::raw('SUM(CASE WHEN transactions.bulan = 5 THEN transactions.pencicilan_rutin + pencicilan_bertahap END) AS may_value'),
                DB::raw('SUM(CASE WHEN transactions.bulan = 6 THEN transactions.pencicilan_rutin + pencicilan_bertahap END) AS june_value'),
                DB::raw('SUM(CASE WHEN transactions.bulan = 7 THEN transactions.pencicilan_rutin + pencicilan_bertahap END) AS july_value'),
                DB::raw('SUM(CASE WHEN transactions.bulan = 8 THEN transactions.pencicilan_rutin + pencicilan_bertahap END) AS ags_value'),
                DB::raw('SUM(CASE WHEN transactions.bulan = 9 THEN transactions.pencicilan_rutin + transactions.pencicilan_bertahap END) AS sep_value'),
                DB::raw('SUM(CASE WHEN transactions.bulan = 10 THEN transactions.pencicilan_rutin + transactions.pencicilan_bertahap END) AS oct_value'),
                DB::raw('SUM(CASE WHEN transactions.bulan = 11 THEN transactions.pencicilan_rutin + transactions.pencicilan_bertahap END) AS nov_value'),
                DB::raw('SUM(CASE WHEN transactions.bulan = 12 THEN transactions.pencicilan_rutin + transactions.pencicilan_bertahap END) AS dec_value'),                
                DB::raw('SUM(pencicilan_rutin + pencicilan_bertahap) AS grandTotal'),
            )
            ->leftJoin('t_user', 'transactions.code', '=', 't_user.code')
            ->leftJoin('lampiran', 'transactions.code', '=', 'lampiran.code')
            ;


        // Filter berdasarkan tahun, unit, status
        if ($request->has('tahun') && $request->get('tahun')) {
            $query->where('transactions.year', $request->tahun);
        }

        if ($request->has('tahun_spp') && $request->get('tahun_spp')) {
            $query->whereYear('lampiran.tanggal_spp', $request->tahun_spp);
        }

        if ($request->has('unit') && $request->get('unit')) {
            $query->where('lampiran.unit', $request->unit);
        }

        if ($request->has('status') && $request->get('status')) {
            $query->where('lampiran.status_karyawan', $request->status);
        }
        if ($request->has('status_lunas') && in_array($request->status_lunas, ['Lunas', 'Belum Lunas'])) {
            $query->whereRaw('CASE WHEN (lampiran.hutang - COALESCE((SELECT SUM(t.pencicilan_rutin + t.pencicilan_bertahap)FROM transactions t WHERE t.code = transactions.code), 0)) = 0 THEN "Lunas" ELSE "Belum Lunas" END = ?', [$request->status_lunas]);
        }

        $transactions = $query->groupBy('transactions.code', 't_user.nama', 'lampiran.no_spp', 'lampiran.tanggal_spp', 'lampiran.status_karyawan', 'lampiran.unit', 'lampiran.hutang')
        ->get();

        $total_jan = $transactions->sum('jan_value');
        $total_feb = $transactions->sum('feb_value');
        $total_mar = $transactions->sum('mar_value');
        $total_apr = $transactions->sum('apr_value');
        $total_may = $transactions->sum('may_value');
        $total_june = $transactions->sum('june_value');
        $total_july = $transactions->sum('july_value');
        $total_ags = $transactions->sum('ags_value');
        $total_sep = $transactions->sum('sep_value');
        $total_oct = $transactions->sum('oct_value');
        $total_nov = $transactions->sum('nov_value');
        $total_dec = $transactions->sum('dec_value');
        $total_grand = $transactions->sum('grandTotal');
        $total_sisa = $transactions->sum('sisa_sht');
        $total_dibayarkan = $transactions->sum('total_dibayarkan');

        // Ambil data untuk unit, status, dan tahun
        $units = Lampiran::select('unit')->distinct()->get();
        $status = Lampiran::select('status_karyawan')->distinct()->get();
        $years = Transaction::select('year')->distinct()->pluck('year');
        $years_spp = Lampiran::selectRaw('YEAR(tanggal_spp) as year_spp')->distinct()->pluck('year_spp');

        // Kirim data ke view
        return view('admin.monitor_pembayaran', compact('transactions','total_sisa','total_dibayarkan','years_spp','total_jan', 'total_feb', 'total_mar', 'total_apr', 'total_may', 'total_june', 'total_july', 'total_ags', 'total_sep', 'total_oct', 'total_nov', 'total_dec', 'total_grand', 'units', 'status', 'years'));
    }
}
