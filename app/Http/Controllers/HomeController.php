<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

   public function admin()
   {
       // Tambahkan middleware tambahan jika diperlukan
       return view('admin.home');
   }

   public function user()
   {
    $user = Auth::user();
    // $transactions = $user->transactions->get();
    return view('user.home',  compact('user'));
   }
}
