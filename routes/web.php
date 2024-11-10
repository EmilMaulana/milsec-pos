<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\HomeController;
use App\Services\WhatsappService;
use App\Http\Controllers\WhatsappController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::resource('products', ProductController::class)->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/product', [ProductController::class, 'index'])->name('product.index');
        Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
        Route::get('/product/{product:slug}/edit', [ProductController::class, 'edit'])->name('product.edit');
        Route::get('/product/{product:slug}/delete', [ProductController::class, 'destroy'])->name('product.destroy');
        Route::get('/product/export', [ProductController::class, 'export'])->name('product.export');
        Route::get('/product/template', [ProductController::class, 'template'])->name('product.template');
        // Route::get('/product/{id}/barcode', [ProductController::class, 'generateBarcode'])->name('product.barcode');
        // Route::get('/product/{id}/barcode/download', [ProductController::class, 'downloadBarcode'])->name('product.barcode.download');
        Route::get('/product/{id}/barcode', [ProductController::class, 'generateBarcode'])->name('product.barcode');
        Route::get('/product/{id}/barcode/download', [ProductController::class, 'downloadBarcode'])->name('product.barcode.download');


        Route::get('/transactions', [TransactionsController::class, 'index'])->name('transaction.index');
        Route::get('/transactions/history', [TransactionsController::class, 'history'])->name('transaction.history');
        Route::get('/transactions/print', [TransactionsController::class, 'print'])->name('transaction.print');

        
    });
});

Route::middleware(['auth','owner'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/report/transactions', [ReportController::class, 'transaction'])->name('report.transaction');
        Route::get('/report/modal', [ReportController::class, 'modal'])->name('report.modal');

        Route::get('/store', [StoreController::class, 'index'])->name('store.index');
        Route::get('/store/{store:slug}/edit', [StoreController::class, 'edit'])->name('store.edit');

        Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
        Route::get('/staff/{user:id}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    });
});


Route::get('/send-message', function () {
    return view('send-message');
});

Route::post('/send-message', [WhatsAppController::class, 'sendMessage'])->name('send.message');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profile', 'ProfileController@index')->name('profile');
Route::put('/profile', 'ProfileController@update')->name('profile.update');

Route::get('/about', function () {
    return view('about');
})->name('about');
