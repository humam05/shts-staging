<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin']);


// Rute Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Dashboard Admin
Route::get('/admin', [HomeController::class, 'admin'])->middleware(['auth', 'role:admin']);

Route::prefix('user')->middleware(['auth', 'role:user'])->group(function () {
    Route::get('/', [HomeController::class, 'user'])->name('user.home');
    Route::get('/dashboard', [AdminController::class, 'index'])->name('user.monitor');
});
// Rute Dashboard User

    


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

    //Master Data Panel
    Route::get('/data-panel', [MasterDataController::class, 'index'])->name('admin.masterdata');
    Route::get('/data-panel/detail/{code}', [MasterDataController::class, 'detail'])->name('admin.masterdata.detail');
    Route::get('/data-panel/create', [MasterDataController::class, 'create'])->name('admin.masterdata.create');
    Route::post('/data-panel/create', [MasterDataController::class, 'store'])->name('admin.masterdata.store');
    Route::get('/data-panel/edit/{code}', [MasterDataController::class, 'edit'])->name('admin.masterdata.edit');
    Route::put('/data-panel/edit/{code}', [MasterDataController::class, 'update'])->name('admin.masterdata.update');
    Route::get('/data-panel/autocomplete', [MasterDataController::class, 'autocomplete'])->name('admin.masterdata.autocomplete');
    Route::get('/data-panel/autocomplete_status', [MasterDataController::class, 'status_autocomplete'])->name('admin.masterdata.autocomplete.status');
    Route::get('/data-panel/edit/transactions/{id}', [MasterDataController::class, 'editTransactions'])->name('admin.masterdata.edit.transactions');
    Route::put('/data-panel/transactions/{id}', [MasterDataController::class, 'updateTransactions'])->name('admin.masterdata.update.transactions');
});
