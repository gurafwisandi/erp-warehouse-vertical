<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PengeluaranDetailController;
use App\Http\Controllers\RakController;
use App\Http\Controllers\ReceiveController;
use App\Http\Controllers\ReceiveDetailController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::get('/phpinfo', [DashboardController::class, 'phpinfo'])->name('phpinfo');
Route::group(
    [
        'prefix'     => 'login'
    ],
    function () {
        Route::post('/proses', [LoginController::class, 'authenticate'])->name('login.proses');
        Route::post('/logout', [LoginController::class, 'logout'])->name('login.logout');
    }
);
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::group(
    ['middleware' => 'auth'],
    function () {
        Route::resource('supplier', SupplierController::class);
        Route::resource('user', UsersController::class);
        Route::get('/profile/{id}', [UsersController::class, 'profile'])->name('user.profile');
        Route::patch('/update_profile/{id}', [UsersController::class, 'update_profile'])->name('user.update_profile');
        Route::resource('item', ItemController::class);
        Route::get('/stock/{id}', [ItemController::class, 'stock'])->name('item.stock');
        Route::post('/dropdown', [ItemController::class, 'dropdown'])->name('item.dropdown');
        Route::post('/dropdown_pengeluaran', [ItemController::class, 'dropdown_pengeluaran'])->name('item.dropdown_pengeluaran');
        Route::post('/dropdown_rak', [ItemController::class, 'dropdown_rak'])->name('item.dropdown_rak');
        Route::resource('rak', RakController::class);
        Route::get('/stock_rak/{id}', [RakController::class, 'stock_rak'])->name('rak.stock_rak');
        Route::resource('receive', ReceiveController::class);
        Route::delete('/approve_purchasing/{id}', [ReceiveController::class, 'approve_purchasing'])->name('receive.approve_purchasing');
        Route::delete('/approve_penempatan/{id}', [ReceiveController::class, 'approve_penempatan'])->name('receive.approve_penempatan');
        Route::get('/penempatan/{id}', [ReceiveController::class, 'penempatan'])->name('receive.penempatan');
        Route::post('/dropdown_receive', [ItemController::class, 'dropdown_receive'])->name('item.dropdown_receive');
        Route::resource('receive_detail', ReceiveDetailController::class);
        Route::resource('inventory', InventoryController::class);
        Route::resource('pengeluaran', PengeluaranController::class);
        Route::delete('/approve_pengeluaran/{id}', [PengeluaranController::class, 'approve_pengeluaran'])->name('pengeluaran.approve_pengeluaran');
        Route::get('/acceptance/{id}', [PengeluaranController::class, 'acceptance'])->name('pengeluaran.acceptance');
        Route::patch('/approve_pengembalian/{id}', [PengeluaranController::class, 'approve_pengembalian'])->name('pengeluaran.approve_pengembalian');
        Route::resource('pengeluaran_detail', PengeluaranDetailController::class);
        Route::get('penerimaan', [ReportController::class, 'penerimaan'])->name('report.penerimaan');
        Route::get('rep_pengeluaran', [ReportController::class, 'rep_pengeluaran'])->name('report.rep_pengeluaran');
        Route::get('rep_sales', [ReportController::class, 'rep_sales'])->name('report.rep_sales');
        Route::get('rep_item', [ReportController::class, 'rep_item'])->name('report.rep_item');
    }
);
