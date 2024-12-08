<?php

namespace App\Http\Controllers;

use App\Models\Lampiran;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserModel;
use Illuminate\Http\Request;

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
        // Ambil filter jika ada
        $unit = $request->input('unit');
        $tahunPensiun = $request->input('tahun_pensiun');
        $statusLunas = $request->input('status_lunas');

        // Ambil data unit dari model Lampiran
        $units = Lampiran::select('unit')->distinct()->get();

        // Ambil data user sesuai dengan filter
        $users = UserModel::query()
            ->with(['transactions', 'lampiran']);

        // Jika filter unit ada, filter berdasarkan unit yang ada di lampiran
        if ($unit) {
            $users->whereHas('lampiran', function ($query) use ($unit) {
                $query->where('unit', $unit);
            });
        }

        // Jika filter tahun pensiun ada, filter transaksi berdasarkan tahun
        if ($tahunPensiun) {
            $users->whereHas('transactions', function ($query) use ($tahunPensiun) {
                $query->where('year', $tahunPensiun);
            });
        }

        // Jika filter status lunas ada, filter berdasarkan status
        if ($statusLunas) {
            $users->whereHas('transactions', function ($query) use ($statusLunas) {
                $query->where('status_lunas', $statusLunas);
            });
        }

        // Ambil semua user yang terfilter
        $users = $users->get();

        // Siapkan array untuk hasil total pencicilan per bulan
        $bulanList = range(1, 12); // Untuk bulan Januari - Desember
        $dataUsers = collect(); // Menggunakan Collection

        foreach ($users as $user) {
            // Ambil semua transaksi user
            $transactions = $user->transactions;

            // Filter transaksi untuk tahun yang dipilih, jika ada
            if ($tahunPensiun) {
                $transactions = $transactions->where('year', $tahunPensiun);
            } else {
                // Jika tidak ada tahun yang dipilih, gunakan tahun saat ini
                $currentYear = date('Y');
                $transactions = $transactions->where('year', $currentYear);
            }

            // Inisialisasi total pencicilan per bulan (1-12) dengan nilai 0
            $totalPencicilanPerBulan = array_fill(1, 12, 0);

            // Proses transaksi untuk menghitung total cicilan per bulan
            foreach ($transactions as $transaction) {
                $pencicilanRutin = $transaction->pencicilan_rutin ?? 0;
                $pencicilanBertahap = $transaction->pencicilan_bertahap ?? 0;
                $totalPencicilan = $pencicilanRutin + $pencicilanBertahap;

                // Tambahkan ke bulan terkait
                $totalPencicilanPerBulan[$transaction->bulan] += $totalPencicilan;
            }

            // Hitung total pencicilan pertahun dengan menjumlahkan seluruh bulan
            $totalPencicilanPerTahun = array_sum($totalPencicilanPerBulan);

            // Masukkan data user dan pencicilan per bulan ke dalam Collection
            $dataUsers->push([
                'user' => $user,
                'total_pencicilan_per_bulan' => $totalPencicilanPerBulan,
                'total_pencicilan_pertahun' => $totalPencicilanPerTahun,
            ]);
        }

        // Hitung total pencicilan per bulan across all users
        $totalPerBulan = array_fill(1, 12, 0);
        foreach ($dataUsers as $data) {
            foreach ($data['total_pencicilan_per_bulan'] as $bulan => $total) {
                $totalPerBulan[$bulan] += $total;
            }
        }
        $totalPertahun = array_sum($totalPerBulan);

        // Kirim data ke view
        return view('admin.monitor_pembayaran', compact('dataUsers', 'bulanList', 'units', 'totalPerBulan', 'totalPertahun'));
    }
    
}
