<?php

use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\RegisterController;

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

Route::get('/', function () {
    return view('Landing Page.index');
});

Route::get('/fitur', function () {
    return view('Landing Page.fitur');
});

Route::get('/about', function () {
    return view('Landing Page.about');
});

Route::get('/contact', function () {
    return view('Landing Page.contact');
});

// untuk menangani register
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    //Route Profile
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');

    //Route Income
    Route::get('/income', [IncomeController::class, 'index'])->name('index.income');
    Route::get('/form-income', [IncomeController::class, 'create'])->name('create.income');
    Route::post('/store-income', [IncomeController::class, 'store'])->name('store.income');
    Route::get('/edit-income/{id}', [IncomeController::class, 'edit'])->name('edit.income');
    Route::delete('/delete-income/{id}', [IncomeController::class, 'destroy'])->name('delete.income');
    Route::put('/update-income/{id}', [IncomeController::class, 'update'])->name('update.income');

    //Route Expense
    Route::get('/expense', [ExpenseController::class, 'index'])->name('index.expense');
    Route::get('/form-expense', [ExpenseController::class, 'create'])->name('create.expense');
    Route::post('/store-expense', [ExpenseController::class, 'store'])->name('store.expense');
    Route::get('/edit-expense/{id}', [ExpenseController::class, 'edit'])->name('edit.expense');
    Route::delete('/delete-expense/{id}', [ExpenseController::class, 'destroy'])->name('delete.expense');
    Route::put('/update-expense/{id}', [ExpenseController::class, 'update'])->name('update.expense');

    //Route Debts
    Route::get('/debt', [DebtController::class, 'index'])->name('index.debt');
    Route::get('/form-debt', [DebtController::class, 'create'])->name('create.debt');
    Route::post('/store-debt', [DebtController::class, 'store'])->name('store.debt');
    Route::get('/edit-debt/{id}', [DebtController::class, 'edit'])->name('edit.debt');
    Route::delete('/delete-debt/{id}', [DebtController::class, 'destroy'])->name('delete.debt');
    Route::put('/update-debt/{id}', [DebtController::class, 'update'])->name('update.debt');

    // Routes Bills
    Route::get('/bill', [BillController::class, 'index'])->name('index.bill');
    Route::get('/form-bill', [BillController::class, 'create'])->name('create.bill');
    Route::post('/store-bill', [BillController::class, 'store'])->name('store.bill');
    Route::get('/edit-bill/{id}', [BillController::class, 'edit'])->name('edit.bill');
    Route::delete('/delete-bill/{id}', [BillController::class, 'destroy'])->name('delete.bill');
    Route::put('/update-bill/{id}', [BillController::class, 'update'])->name('update.bill');

    // Route Report
    Route::get('/report', [ReportController::class, 'index'])->name('index.report');
    Route::get('/report/pdf', [ReportController::class, 'downloadPDF'])->name('report.pdf');
});



// Rute untuk menampilkan halaman notifikasi verifikasi
Route::get('/email/verify', [EmailVerificationController::class, 'show'])
    ->middleware('auth')
    ->name('verification.notice');

// Rute untuk menangani verifikasi email
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

// Rute untuk mengirim ulang link verifikasi email
Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

// mendapatkan tanggal jatuh tempo tagihan hari ini
Route::get('/due-bills', [BillController::class, 'getDueBills']);

// penanda lunas
Route::put('/bills/mark-as-paid/{id}', [BillController::class, 'markAsPaid'])->name('bills.markAsPaid');

Route::get('/due-debts', [DebtController::class, 'getDueDebts'])->name('due-debts');
