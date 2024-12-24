<?php

namespace App\Http\Controllers;

use App\Imports\DataImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Facades\Excel;

class ImportPembayaranController extends Controller
{
    public function index()
    {
        return view('admin.panel.import');
    }

    public function import(Request $request)
    {
        $file = $request->file('file');
        Excel::import(new DataImport, $file);

        return back()->with('success', 'Data has been imported successfully!');
    }
}
