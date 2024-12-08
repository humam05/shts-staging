<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter yang dipilih oleh pengguna
        $year = $request->input('year');
        $category = $request->input('category');
    
        // Query transaksi dengan filter tahun dan kategori
        $transactionsQuery = DB::table('transactions')
            ->select(DB::raw('bulan, year, SUM(pencicilan_rutin) + SUM(pencicilan_bertahap) AS total'))
            ->groupBy('year', 'bulan');
    
        // Menambahkan filter untuk tahun dan kategori jika ada
        if ($year) {
            $transactionsQuery->where('year', $year);
        }
    
        if ($category) {
            if ($category === 'KP') {
                $transactionsQuery->where('code', 'LIKE', '%KP%');
            } elseif ($category === 'KL') {
                $transactionsQuery->where('code', 'LIKE', '%KL%');
            }
        }
        
    
        // Ambil data transaksi
        $transactions = $transactionsQuery->get();

        // $categories = ['KP', 'KL'];
    
        // Hitung total semua transaksi per bulan
        $totalPerMonth = $transactions->sum('total');  // Menghitung jumlah dari kolom 'total' yang telah dijumlahkan

        $years = DB::table('transactions')
            ->select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->get();
    
        // Kirim data ke view
        return view('admin.rekap',compact('transactions', 'totalPerMonth', 'years'));
    }
    

    public function destroy(Request $request,$year, $bulan)
    {

        $category = $request->input('category');

        if ($category) {
            // Hapus transaksi berdasarkan kategori yang dipilih (KP atau KL)
            DB::table('transactions')
                ->where('year', $year)
                ->where('bulan', $bulan)
                ->where('code', 'LIKE', '%' . $category . '%')
                ->delete();
        } else {
            // Jika kategori tidak dipilih, hapus semua transaksi pada bulan dan tahun tersebut
            DB::table('transactions')
                ->where('year', $year)
                ->where('bulan', $bulan)
                ->delete();
        }

        return redirect()->route('admin.rekap')->with('success', 'Transaksi untuk bulan ' . $bulan . ' tahun ' . $year . ' berhasil dihapus.');
    }
}
