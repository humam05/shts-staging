<?php

namespace App\Http\Controllers;

use App\Models\Lampiran;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserModel;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        // Pastikan hanya admin yang bisa mengakses
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        // Build base query for users
        $query = UserModel::query();
        
        // Apply filters before fetching the data
        $this->applyFilters($query, $request);
        
        // Apply sorting by date (tanggal_spp) if requested
        $this->applyDateSorting($query, $request);
        
        // Fetch users with the associated data
        $users = $query->with(['lampiran', 'transactions'])->get();
        
        // Process each user to calculate total payments and remaining balance
        $users = $this->processUserData($users);
        
        // Filter by 'status_lunas' if requested
        $users = $this->filterByStatusLunas($users, $request);
    
        // Paginate the results before fetching related data
        $users = $this->paginateUsers($users, $request);
        
        // Fetch filter options for units, statuses, years, and months
        $units = Lampiran::select('unit')->distinct()->get();
        $status = Lampiran::select('status_karyawan')->distinct()->get();
        $years = Lampiran::selectRaw('YEAR(tanggal_spp) as year')->distinct()->pluck('year');
        $months = Lampiran::selectRaw('MONTH(tanggal_spp) as month')->distinct()->pluck('month');
        
        // Calculate total payments and debts for the unit or status filters
        $totalPencicilan = $this->calculateTotalPencicilan($request);
        $totalHutang = $this->calculateTotalHutang($request);
        
        // Calculate remaining balance (sisa_sht)
        $totalSisaSht = $totalPencicilan->total_pembayaran - $totalHutang->total_hutang;
        
        // Return the view with the necessary data
        return view('admin.admin', compact('users', 'years', 'months', 'totalHutang', 'totalPencicilan', 'totalSisaSht', 'status', 'units'));
    }
    
    private function applyFilters($query, $request)
    {
        if ($request->filled('status')) {
            $query->whereHas('lampiran', function ($query) use ($request) {
                $query->where('status_karyawan', $request->status);
            });
        }
    
        if ($request->filled('unit')) {
            $query->whereHas('lampiran', function ($query) use ($request) {
                $query->where('unit', $request->unit);
            });
        }
    
        if ($request->filled('tanggal_spp')) {
            $query->whereHas('lampiran', function ($query) use ($request) {
                $query->whereDate('tanggal_spp', '=', $request->tanggal_spp);
            });
        }
    
        if ($request->filled('tahun')) {
            $query->whereHas('lampiran', function ($query) use ($request) {
                $query->whereYear('tanggal_spp', '=', $request->tahun);
            });
        }
    
        if ($request->filled('bulan')) {
            $query->whereHas('lampiran', function ($query) use ($request) {
                $query->whereMonth('tanggal_spp', '=', $request->bulan);
            });
        }
    }
    
    private function applyDateSorting($query, $request)
    {
        // Check if sorting by date is requested
        if ($request->filled('sort_tanggal')) {
            $direction = $request->input('sort_tanggal') == 'desc' ? 'desc' : 'asc';
            $query->leftJoin('lampiran', 't_user.code', '=', 'lampiran.code') // Pastikan nama tabel benar
                  ->orderBy('lampiran.tanggal_spp', $direction)
                  ->select('t_user.*'); // Memilih kolom dari t_user untuk menghindari konflik
        }
    }
    
    private function processUserData($users)
    {
        foreach ($users as $user) {
            $user->totalPencicilan = $user->transactions->sum(function ($transaction) {
                return ($transaction->pencicilan_rutin ?? 0) + ($transaction->pencicilan_bertahap ?? 0);
            });
    
            $hutang = $user->lampiran->hutang ?? 0;
            $user->sisa_sht = $hutang - $user->totalPencicilan;
            $user->status_lunas = $user->sisa_sht <= 0 ? 'Lunas' : 'Belum Lunas';
        }
    
        return $users;
    }
    
    private function filterByStatusLunas($users, $request)
    {
        if ($statusLunas = $request->input('status_lunas')) {
            return $users->filter(function ($user) use ($statusLunas) {
                return $user->status_lunas === $statusLunas;
            });
        }
    
        return $users;
    }
    
    private function paginateUsers($users, $request)
    {
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $users->forPage($request->input('page', 1), 10),
            $users->count(),
            10,
            $request->input('page', 1),
            ['path' => url()->current()]
        );
    }
    
    private function calculateTotalPencicilan($request)
    {
        $query = DB::table('transactions')
                   ->selectRaw('SUM(pencicilan_rutin + pencicilan_bertahap) AS total_pembayaran')
                   ->join('lampiran', 'transactions.code', '=', 'lampiran.code');
    
        if ($request->filled('unit')) {
            $query->where('lampiran.unit', $request->unit);
        }
    
        if ($request->filled('status')) {
            $query->where('lampiran.status_karyawan', $request->status);
        }
    
        if ($request->filled('tanggal_spp')) {
            $query->whereDate('lampiran.tanggal_spp', '=', $request->tanggal_spp);
        }
    
        if ($request->filled('tahun')) {
            $query->whereYear('lampiran.tanggal_spp', '=', $request->tahun);
        }
    
        if ($request->filled('bulan')) {
            $query->whereMonth('lampiran.tanggal_spp', '=', $request->bulan);
        }
    
        return $query->first();
    }
    
    private function calculateTotalHutang($request)
    {
        $query = Lampiran::selectRaw('SUM(hutang) AS total_hutang');
    
        if ($request->filled('unit')) {
            $query->where('unit', $request->unit);
        }
    
        if ($request->filled('status')) {
            $query->where('status_karyawan', $request->status);
        }
    
        if ($request->filled('tanggal_spp')) {
            $query->whereDate('tanggal_spp', '=', $request->tanggal_spp);
        }
    
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_spp', '=', $request->tahun);
        }
    
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_spp', '=', $request->bulan);
        }
    
        return $query->first();
    }
       

    // Tampilkan form tambah user
    public function createUser()
    {
        return view('admin.create_user');
    }

    // Proses tambah user
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
