<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rute Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Dashboard Admin
Route::get('/admin', [HomeController::class, 'admin'])->middleware(['auth', 'role:admin']);

// Rute Dashboard User
Route::get('/user', [HomeController::class, 'user'])->middleware(['auth', 'role:user'])->name('user');

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/form-pembayaran', [PembayaranController::class, 'index'])->name('admin.form.pembayaran');
    Route::get('/monitor-pembayaran', [PembayaranController::class, 'monitor'])->name('admin.monitor.pembayaran');
    Route::get('/rekap', [RekapController::class, 'index'])->name('admin.rekap');
    Route::delete('/rekap/{year}/{bulan}', [RekapController::class, 'destroy'])->name('admin.rekap.destroy');
    Route::get('/testes', [PembayaranController::class, 'autocomplete'])->name('admin.autocomplete');


    // User Management
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');

    // Edit dan Delete User
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');


    // Transaction Management 
    Route::get('/users/{user}/transactions', [AdminController::class, 'transactions'])->name('admin.transactions');
    Route::get('/transactions/create', [AdminController::class, 'createTransaction'])->name('admin.transactions.create');
    Route::post('/transactions', [AdminController::class, 'storeTransaction'])->name('admin.transactions.store');

    // Tambahkan Rute Edit dan Hapus Transaksi
    Route::get('/transactions/{transaction}/edit', [AdminController::class, 'editTransaction'])->name('admin.transactions.edit');
    Route::put('/transactions/{transaction}', [AdminController::class, 'updateTransaction'])->name('admin.transactions.update');
    Route::delete('/transactions/{transaction}', [AdminController::class, 'deleteTransaction'])->name('admin.transactions.delete');
});
