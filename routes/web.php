<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;

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
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/home', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/deposit', [WalletController::class, 'getDepositForm'])->name('wallet.deposit.form');
    Route::post('/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::get('/deposit/history', [WalletController::class, 'depositHistory'])->name('deposit.history');

    Route::get('/transfer', [WalletController::class, 'getTransferForm'])->name('wallet.transfer.form');
    Route::post('/transfer', [WalletController::class, 'transfer'])->name('wallet.transfer');
    Route::get('/transfer/history', [WalletController::class, 'transferHistory'])->name('transfer.history');
});

require __DIR__.'/auth.php';
