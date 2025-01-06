<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportPembayaranController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\MonthlyReportController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin']);


// Rute Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Dashboard Admin
Route::get('/admin', [HomeController::class, 'admin'])->middleware(['auth', 'role:admin']);

Route::prefix('user')->middleware(['auth', 'role:user'])->group(function () {
    Route::get('/', [HomeController::class, 'user'])->name('user.home');
    Route::get('/dashboard', [AdminController::class, 'index'])->name('user.monitor');
});

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/form-pembayaran', [PembayaranController::class, 'index'])->name('admin.form.pembayaran');
    Route::get('/monitor-pembayaran', [PembayaranController::class, 'monitor'])->name('admin.monitor.pembayaran');
    Route::get('/rekap', [RekapController::class, 'index'])->name('admin.rekap');
    Route::delete('/rekap/{year}/{bulan}', [RekapController::class, 'destroy'])->name('admin.rekap.destroy');
    Route::get('/testes', [PembayaranController::class, 'autocomplete'])->name('admin.autocomplete');


    Route::post('/transactions', [PembayaranController::class, 'storeTransaction'])->name('admin.transactions.store');

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
    Route::get('/data-panel/manage-user', [MasterDataController::class, 'manageUser'])->name('admin.masterdata.manage_user');
    Route::get('/data-panel/manage-user/edit/{id}', [MasterDataController::class, 'editUser'])->name('admin.masterdata.manage_user.edit');
    Route::delete('/data-panel/manage-user/delete/{id}', [MasterDataController::class, 'deleteUser'])->name('admin.masterdata.manage_user.delete');
    Route::get('/data-panel/monthly-report/', [MonthlyReportController::class, 'index'])->name('admin.masterdata.monthly_report');
    Route::get('/data-panel/import/', [ImportPembayaranController::class, 'index'])->name('admin.masterdata.import_view');
    Route::post('/data-panel/import/', [ImportPembayaranController::class, 'import'])->name('admin.masterdata.import');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('post.register');
});

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/form-pembayaran', [PembayaranController::class, 'index'])->name('admin.form.pembayaran');
    Route::get('/monitor-pembayaran', [PembayaranController::class, 'monitor'])->name('admin.monitor.pembayaran');
    Route::get('/rekap', [RekapController::class, 'index'])->name('admin.rekap');
    Route::delete('/rekap/{year}/{bulan}', [RekapController::class, 'destroy'])->name('admin.rekap.destroy');
    Route::get('/testes', [PembayaranController::class, 'autocomplete'])->name('admin.autocomplete');


    Route::post('/transactions', [PembayaranController::class, 'storeTransaction'])->name('admin.transactions.store');

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
    Route::get('/data-panel/manage-user', [MasterDataController::class, 'manageUser'])->name('admin.masterdata.manage_user');
    Route::get('/data-panel/manage-user/edit/{id}', [MasterDataController::class, 'editUser'])->name('admin.masterdata.manage_user.edit');
    Route::delete('/data-panel/manage-user/delete/{id}', [MasterDataController::class, 'deleteUser'])->name('admin.masterdata.manage_user.delete');
    Route::get('/data-panel/monthly-report/', [MonthlyReportController::class, 'index'])->name('admin.masterdata.monthly_report');
    Route::get('/data-panel/import/', [ImportPembayaranController::class, 'index'])->name('admin.masterdata.import_view');
    Route::post('/data-panel/import/', [ImportPembayaranController::class, 'import'])->name('admin.masterdata.import');


    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('post.register');
});
