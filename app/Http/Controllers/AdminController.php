<?php

namespace App\Http\Controllers;

use App\Exports\RekapExport;
use App\Models\Lampiran;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserModel;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

            // Filter by 'status'
    if ($request->has('status') && $request->status) {
        $query->where('lampiran.status_karyawan', $request->status);
    }

    // Filter by 'unit'
    if ($request->has('unit') && $request->unit) {
        $query->where('lampiran.unit', $request->unit);
    }

    // Filter by 'tahun' (year)
    if ($request->has('tahun') && $request->tahun) {
        $query->whereYear('lampiran.tanggal_spp', $request->tahun);
    }

    // Filter by 'bulan' (month)
    if ($request->has('bulan') && $request->bulan) {
        $query->whereMonth('lampiran.tanggal_spp', $request->bulan);
    }

    // Sort by 'sort_tanggal' (date)
    if ($request->has('sort_tanggal') && in_array($request->sort_tanggal, ['asc', 'desc'])) {
        $query->orderBy('lampiran.tanggal_spp', $request->sort_tanggal);
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

        // dd($users);
        $totalHutang = $users->sum('hutang');
        $totalPencicilan = $users->sum('total_pembayaran');
        $totalSisaSht = $users->sum('sisa_sht');

        if ($request->has('export') && $request->get('export') == 'excel') {
            $filters = $request->only(['status_lunas', 'status', 'unit', 'tahun', 'bulan', 'sort_tanggal']);
            return Excel::download(new RekapExport($filters), 'users_data.xlsx');
        }            
    
        // Fetch filter options for units, statuses, years, and months
        $units = DB::table('lampiran')->select('unit')->distinct()->get();
        $status = Lampiran::select('status_karyawan')->distinct()->get();
        $years = Lampiran::selectRaw('YEAR(tanggal_spp) as year')->distinct()->pluck('year');
        $months = Lampiran::selectRaw('MONTH(tanggal_spp) as month')->distinct()->pluck('month');


        // Return the view with the necessary data
        return view('admin.admin', compact(
            'users',
            'years',
            'months',
            'totalHutang',
            'totalPencicilan',
            'totalSisaSht',
            'status',
            'units'
        ));
    }

    public function createUser()
    {
        return view('admin.create_user');
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'kode_user' => 'required|unique:users,kode_user',
            'nama' => 'required|string|max:255',
            'role' => 'required|in:admin,user',
            'password' => 'required|min:6|confirmed',
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.dashboard')->with('success', 'User berhasil ditambahkan.');
    }

    public function editUser(Request $request, User $user)
    {
        $tanggal_spp = $user->lampiran->tanggal_spp ?? now(); // Gunakan tanggal sekarang jika null
        return view('admin.edit_user', compact('user', 'tanggal_spp'));
    }

    public function updateUser(Request $request, User $user)
    {
        // Validasi data user utama
        $data = $request->validate([
            'kode_user' => 'required',
            'nama' => 'required|string|max:255',
        ]);

        // Validasi tambahan untuk data lampiran
        $lampiranData = $request->validate([
            'status_karyawan' => 'nullable|string|max:255',
            'tanggal_spp' => 'nullable|date',
            'no_spp' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:255',
            'hutang' => 'nullable|numeric',
        ]);

        // Perbarui data user
        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Update data user
        $user->update($data);

        // Perbarui data lampiran jika ada
        if ($user->lampiran) {
            $user->lampiran->update($lampiranData);
        } else {
            // Jika lampiran belum ada, buat lampiran baru
            $user->lampiran()->create($lampiranData);
        }

        // Redirect dengan pesan sukses
        return redirect()->route('admin.dashboard')->with('success', 'User dan lampiran berhasil diperbarui.');
    }

    public function deleteUser(User $user)
    {
        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'User berhasil dihapus.');
    }

    // Tampilkan data transaksi
    public function transactions($code)
    {
        // Mengambil data transaksi berdasarkan 'code' dari UserModel
        $user = UserModel::with('transactions')->findOrFail($code);

        // Jika tidak ada transaksi, kembalikan view dengan pesan
        if ($user->transactions->isEmpty()) {
            return view('admin.transaction', ['user' => $user, 'transactions' => null])
                ->with('error', 'Tidak ada transaksi ditemukan untuk pengguna ini.');
        }

        return view('admin.transaction', compact('user'));
    }

    public function editTransaction(Transaction $transaction)
    {
        $users = User::where('role', 'user')->get();
        $months = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];
        $years = range(date('Y'), date('Y') + 10);

        return view('admin.edit_transaction', compact('transaction', 'users', 'months', 'years'));
    }

    public function updateTransaction(Request $request, Transaction $transaction)
    {
        $data = $request->validate([
            'kode_user' => 'required|unique:users,kode_user',
            'bulan' => 'required',
            'year' => 'required|integer|min:1900|max:2100',
            'pencicilan_rutin' => 'required|numeric|min:0',
            'pencicilan_bertahap' => 'required|numeric|min:0',
        ]);

        $transaction->update($data);

        return redirect()->route('admin.transactions')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function deleteTransaction(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('admin.transactions')->with('success', 'Transaksi berhasil dihapus.');
    }


    // Proses tambah transaksi
    public function storeTransaction(Request $request)
    {
        // Validasi input untuk memastikan kode_user ada di database users
        $data = $request->validate([
            'kode_user' => 'required|exists:users,kode_user',
            'pencicilan_rutin' => 'required',
            'pencicilan_bertahap' => 'required',
        ]);

        // Cari user berdasarkan kode_user
        $user = User::where('kode_user', $data['kode_user'])->firstOrFail();

        // Siapkan data transaksi
        // bulan dan year kita set otomatis dari tanggal saat ini
        $transactionData = [
            'user_id' => $user->id,
            'bulan' => (int) date('n'), // Akan menghasilkan integer untuk bulan ini
            'year' => (int) date('Y'),
            'pencicilan_rutin' => $data['pencicilan_rutin'],
            'pencicilan_bertahap' => $data['pencicilan_bertahap'],
        ];

        // Simpan data transaksi
        Transaction::create($transactionData);

        // Redirect atau kembali dengan pesan sukses
        return redirect()->route('admin.transactions', $user->id)->with('success', 'Transaksi berhasil ditambahkan.');
    }
}
