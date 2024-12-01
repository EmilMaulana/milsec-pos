<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\ProductController;
// use App\Http\Controllers\AttendanceController;
// use App\Http\Controllers\ReportController;
// use App\Http\Controllers\StaffController;
// use App\Http\Controllers\StoreController;
// use App\Http\Controllers\TransactionsController;
// use App\Http\Controllers\HomeController;
// use App\Http\Controllers\WhatsAppController;
// use App\Http\Controllers\CashflowController;
use App\Http\Controllers\Api\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::apiResource('products', ProductController::class);

// routes/api.php


// Endpoint untuk produk
// Route::middleware('api')->prefix('dashboard')->group(function () {
//     Route::get('/product', [ProductController::class, 'index'])->name('product.index');
//     Route::post('/product', [ProductController::class, 'store'])->name('product.store');
//     Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product.show');
//     Route::put('/product/{product:slug}', [ProductController::class, 'update'])->name('product.update');
//     Route::delete('/product/{product:slug}', [ProductController::class, 'destroy'])->name('product.destroy');
//     Route::get('/product/export', [ProductController::class, 'export'])->name('product.export');
//     Route::get('/product/template', [ProductController::class, 'template'])->name('product.template');
//     Route::get('/product/{id}/barcode', [ProductController::class, 'generateBarcode'])->name('product.barcode');
//     Route::get('/product/{id}/barcode/download', [ProductController::class, 'downloadBarcode'])->name('product.barcode.download');

//     // // Endpoint untuk transaksi
//     // Route::get('/transactions', [TransactionsController::class, 'index'])->name('transaction.index');
//     // Route::get('/transactions/history', [TransactionsController::class, 'history'])->name('transaction.history');
//     // Route::get('/transactions/print', [TransactionsController::class, 'print'])->name('transaction.print');

//     // // Endpoint untuk cashflow
//     // Route::get('/cashflow', [CashflowController::class, 'index'])->name('cashflow.index');
//     // Route::post('/cashflow', [CashflowController::class, 'store'])->name('cashflow.store');
//     // Route::get('/cashflow/{cashflow:id}', [CashflowController::class, 'show'])->name('cashflow.show');
//     // Route::put('/cashflow/{cashflow:id}', [CashflowController::class, 'update'])->name('cashflow.update');
//     // Route::delete('/cashflow/{cashflow:id}', [CashflowController::class, 'destroy'])->name('cashflow.destroy');

//     // // Endpoint untuk absensi
//     // Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
//     // Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
//     // Route::get('/attendance/{attendance:id}', [AttendanceController::class, 'show'])->name('attendance.show');
//     // Route::put('/attendance/{attendance:id}', [AttendanceController::class, 'update'])->name('attendance.update');
//     // Route::delete('/attendance/{attendance:id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
// });

// // Endpoint untuk laporan, store, dan staff yang hanya bisa diakses oleh owner
// Route::middleware(['auth:api', 'owner'])->prefix('dashboard')->group(function () {
//     Route::get('/report/transactions', [ReportController::class, 'transaction'])->name('report.transaction');
//     Route::get('/report/modal', [ReportController::class, 'modal'])->name('report.modal');

//     Route::get('/store', [StoreController::class, 'index'])->name('store.index');
//     Route::get('/store/{store:slug}', [StoreController::class, 'show'])->name('store.show');
//     Route::put('/store/{store:slug}', [StoreController::class, 'update'])->name('store.update');

//     Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
//     Route::get('/staff/{user:id}', [StaffController::class, 'show'])->name('staff.show');
//     Route::put('/staff/{user:id}', [StaffController::class, 'update'])->name('staff.update');
// });

// // Endpoint untuk WhatsApp
// Route::post('/send-message', [WhatsAppController::class, 'sendMessage'])->name('send.message');

