<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\select;

class MasterDataController extends Controller
{
    public function index()
    {
        $query = DB::table("t_user")->select('t_user.code', 't_user.nama', 'lampiran.no_spp', 'lampiran.tanggal_spp', 'lampiran.status_karyawan', 'lampiran.unit', 'lampiran.hutang')
            ->leftJoin('lampiran', 'lampiran.code', '=', 't_user.code')
            ->groupBy('t_user.code', 't_user.nama', 'lampiran.no_spp', 'lampiran.tanggal_spp', 'lampiran.status_karyawan', 'lampiran.unit', 'lampiran.hutang');
        $data = $query->get();
        return view('admin.panel.datapanel', compact('data'));
    }

    public function create()
    {
        return view('admin.panel.create');
    }

    public function store(Request $request)
    {
        // Validate and store the new data
        $validated = $request->validate([
            'code' => 'required',
            'nama' => 'required',
            'no_spp' => 'required',
            'tanggal_spp' => 'required',
            'status_karyawan' => 'required',
            'hutang' => 'required',
            'unit' => 'required',
        ]);

        DB::table('t_user')->insert([
            'code' => $validated['code'],
            'nama' => $validated['nama'],
        ]);

        DB::table('lampiran')->insert([
            'code' => $validated['code'],
            'no_spp' => $validated['no_spp'],
            'tanggal_spp' => $validated['tanggal_spp'],
            'status_karyawan' => $validated['status_karyawan'],
            'unit' => $validated['unit'],
            'hutang' => $validated['hutang'],

        ]);

        return redirect()->route('admin.masterdata');
    }

    public function edit($code)
    {
        $user = DB::table('t_user')->where('code', $code)->first();
        $lampiran = DB::table('lampiran')->where('code', $code)->first();
        return view('admin.panel.edit', compact('user', 'lampiran'));
    }

    public function detail($code)
{
    $data = DB::table('transactions')
        ->select(
            'transactions.id',
            'transactions.code',
            'transactions.bulan',
            'transactions.year',
            'transactions.pencicilan_rutin',
            'transactions.pencicilan_bertahap',
        )
        ->leftJoin('t_user', 't_user.code', '=', 'transactions.code')
        ->where('transactions.code', $code)
        ->groupBy(
            'transactions.id',
            'transactions.code',
            'transactions.bulan',
            'transactions.year',
            'transactions.pencicilan_rutin',
            'transactions.pencicilan_bertahap',           
        )
        ->orderBy('transactions.bulan', 'ASC')  // Urutkan berdasarkan bulan secara ascending
        ->orderBy('transactions.year', 'ASC')   // Urutkan berdasarkan tahun secara ascending
        
        ->get();

        $user = DB::table('t_user')->select('nama')->where('code',$code)->first();

    return view('admin.panel.detail', compact('data', 'user'));
}


    public function update(Request $request, $code)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'no_spp' => 'nullable',
            'tanggal_spp' => 'nullable',
            'status_karyawan' => 'nullable',
        ]);

        DB::table('t_user')
            ->where('code', $code)
            ->update([
                'nama' => $validated['nama'],
            ]);

        DB::table('lampiran')
            ->where('code', $code)
            ->update([
                'no_spp' => $validated['no_spp'],
                'tanggal_spp' => $validated['tanggal_spp'],
                'status_karyawan' => $validated['status_karyawan'],
            ]);

        return redirect()->route('admin.masterdata');
    }

    public function autocomplete(Request $request)
    {
        // Retrieve the search term from the request
        $term = $request->get('term');

        // Fetch records where the 'unit' column matches the search term
        $units = DB::table('lampiran')
            ->select('unit')
            ->where('unit', 'like', "%$term%")
            ->groupBy('unit')
            ->get();

        // Return the results as a JSON response
        return response()->json($units);
    }

    public function status_autocomplete(Request $request)
    {
        // Retrieve the search term from the request
        $term = $request->get('term');

        // Fetch records where the 'unit' column matches the search term
        $status_karyawan = DB::table('lampiran')
            ->select('status_karyawan')
            ->where('status_karyawan', 'like', "%$term%")
            ->groupBy('status_karyawan')
            ->get();

        // Return the results as a JSON response
        return response()->json($status_karyawan);
    }

    public function editTransactions($id)
    {
        $transactions = DB::table('transactions')
        ->where('id', $id)->first();
        return view('admin.panel.edit_transaction', compact('transactions'));
    }

    public function updateTransactions(Request $request, $id)
{
    // Validasi data yang diinput
    $request->validate([
        'bulan' => 'required',
        'year' => 'required|integer|min:2000|max:2100',
        'pencicilan_rutin' => 'required',
        'pencicilan_bertahap' => 'required',
    ]);

    // Update data transaksi
    DB::table('transactions')
        ->where('id', $id)
        ->update([
            'bulan' => $request->bulan,
            'year' => $request->year,
            'pencicilan_rutin' => $request->pencicilan_rutin,
            'pencicilan_bertahap' => $request->pencicilan_bertahap,
        ]);

    // Redirect kembali dengan pesan sukses
    return redirect()->route('admin.masterdata.edit.transactions', $id)->with('success', 'Transaksi berhasil diperbarui.');
}

}
