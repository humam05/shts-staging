<?php

namespace App\Http\Controllers;

use App\Exports\RekapExport;
use App\Models\Lampiran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function __construct()
    {
        // Pastikan hanya admin dan uset yang bisa mengakses
        $this->middleware(['auth', 'role:admin|user']);
    }

    public function index(Request $request)
    {

        $startDate = $request->input('awal_spp');
        $endDate = $request->input('akhir_spp');

        $start = Carbon::parse($startDate)->startOfMonth();

        $end = Carbon::parse($endDate)->endOfMonth();

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
                DB::raw('lampiran.hutang - COALESCE(SUM(transactions.pencicilan_rutin + transactions.pencicilan_bertahap), 0) as sisa_sht')
            )
            ->leftJoin('transactions', 't_user.code', '=', 'transactions.code')
            ->leftJoin('lampiran', 't_user.code', '=', 'lampiran.code');


        if ($request->has('status_lunas')) {
            if ($request->get('status_lunas') == 'Belum Lunas') {
                $query->having(
                    DB::raw('lampiran.hutang - SUM(transactions.pencicilan_rutin + transactions.pencicilan_bertahap)'),
                    '>',
                    0
                );
            } else if ($request->get('status_lunas') == 'Lunas') {
                $query->having(
                    DB::raw('lampiran.hutang - SUM(transactions.pencicilan_rutin + transactions.pencicilan_bertahap)'),
                    '=',
                    0
                );
            }
        }


        if ($request->has('status') && $request->status) {
            $query->where('lampiran.status_karyawan', $request->status);
        }

        // Filter by 'unit'
        if ($request->has('unit') && $request->unit) {
            $query->where('lampiran.unit', $request->unit);
        }

        // Sort by 'sort_tanggal' (date)
        if ($request->has('sort_tanggal') && in_array($request->sort_tanggal, ['asc', 'desc'])) {
            $query->orderBy('lampiran.tanggal_spp', $request->sort_tanggal);
        }

        if ($startDate) {
            $start = Carbon::parse($startDate)->startOfMonth();
            $query->where('lampiran.tanggal_spp', '>=', $start);
        }

        // Jika 'akhir_spp' diatur, terapkan filter tanggal akhir
        if ($endDate) {
            $end = Carbon::parse($endDate)->endOfMonth();
            $query->where('lampiran.tanggal_spp', '<=', $end);
        }


        $users = $query->groupBy(
            't_user.nama',
            't_user.code',
            'lampiran.tanggal_spp',
            'lampiran.no_spp',
            'lampiran.unit',
            'lampiran.status_karyawan',
            'lampiran.hutang'
        )
            ->get();

        $totalHutang = $users->sum('hutang');
        $totalPencicilan = $users->sum('total_pembayaran');
        $totalSisaSht = $users->sum('sisa_sht');


        if ($request->has('export') && $request->get('export') == 'excel') {
            $filters = $request->only(['status_lunas', 'status', 'unit', 'sort_tanggal', 'awal_spp', 'akhir_spp']);
            return Excel::download(new RekapExport($filters), 'users_data.xlsx');
        }

        // Fetch filter options for units, statuses, years, and months
        $units = DB::table('lampiran')->select('unit')->distinct()->get();

        $status = Lampiran::select('status_karyawan')->distinct()->get();

        // Return the view with the necessary data
        return view('admin.admin', compact(
            'users',
            'totalHutang',
            'totalPencicilan',
            'totalSisaSht',
            'status',
            'units'
        ));
    }
}
