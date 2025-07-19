<?php

use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ManageMenuController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ManageExpensesController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OwnerDashboardController;
use App\Http\Controllers\KasirDashboardController;
use App\Http\Controllers\ManagerDashboardController;


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

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::middleware('role:owner')->group(function () {
        Route::get('/dashboard/owner', [OwnerDashboardController::class, 'index'])->name('dashboard.owner');
    });

    Route::middleware('role:kasir')->group(function () {
        Route::get('/dashboard/kasir', [KasirDashboardController::class, 'index'])->name('dashboard.kasir');
    });

    Route::middleware('role:manager')->group(function () {
        Route::get('/dashboard/manager', [ManagerDashboardController::class, 'index'])->name('dashboard.manager');
    });
});

Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/dashboard/manager', [ManagerDashboardController::class, 'index'])->name('dashboard.manager');

    // Pengeluaran
    Route::get('/pengeluaran/create', [PengeluaranController::class, 'create'])->name('pengeluaran.create');

    // Tagihan
    Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
    Route::get('/tagihan/create', [TagihanController::class, 'create'])->name('tagihan.create');

    // Hutang
    Route::get('/hutang', [HutangController::class, 'index'])->name('hutang.index');
    Route::get('/hutang/create', [HutangController::class, 'create'])->name('hutang.create');
});

Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/dashboard/owner', [OwnerDashboardController::class, 'index'])->name('dashboard.owner');
});

// untuk menangani register
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    //Route Profile
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');

    //Route Income
    Route::get('/daily-income-chart', [IncomeController::class, 'dailyIncomeChart'])->name('daily.income.chart');
    Route::get('/income', [IncomeController::class, 'index'])->name('index.income');
    Route::get('/form-income', [IncomeController::class, 'create'])->name('create.income');
    Route::post('/store-income', [IncomeController::class, 'store'])->name('store.income');
    Route::get('edit-incomes/{id_incomes}', [IncomeController::class, 'edit'])->name('edit.income');
    Route::put('update-incomes/{id_incomes}', [IncomeController::class, 'update'])->name('update.income');

    // Route Manage Menu
    Route::get('/manage-menu', [ManageMenuController::class, 'index'])->name('manage-menu.index');
    Route::get('/manage-menu/{id}/edit', [ManageMenuController::class, 'edit'])->name('manage-menu.edit');
    Route::put('/manage-menu/{id}', [ManageMenuController::class, 'update'])->name('manage-menu.update');
    Route::delete('/manage-menu/{id}', [ManageMenuController::class, 'destroy'])->name('manage-menu.destroy');
    Route::get('/manage-menu/create', [ManageMenuController::class, 'create'])->name('manage-menu.create');
    Route::post('/manage-menu', [ManageMenuController::class, 'store'])->name('manage-menu.store');

    //Route Expense
    Route::get('/expense', [ExpenseController::class, 'index'])->name('index.expense');
    Route::get('/form-expense', [ExpenseController::class, 'create'])->name('create.expense');
    Route::post('/store-expense', [ExpenseController::class, 'store'])->name('store.expense');
    Route::get('/edit-expense/{id}', [ExpenseController::class, 'edit'])->name('edit.expense');
    Route::delete('/delete-expense/{id}', [ExpenseController::class, 'destroy'])->name('delete.expense');
    Route::put('/update-expense/{id}', [ExpenseController::class, 'update'])->name('update.expense');

    // Route Manage Expanses
    Route::get('/manage-expenses', [ManageExpensesController::class, 'index'])->name('manage-expanses.index');
    Route::get('/manage-expanses/{id}/edit', [ManageExpensesController::class, 'edit'])->name('manage-expanses.edit');
    Route::put('/manage-expanses/{id}', [ManageExpensesController::class, 'update'])->name('manage-expanses.update');
    Route::delete('/manage-expanses/{id}', [ManageExpensesController::class, 'destroy'])->name('manage-expanses.destroy');
    Route::get('/manage-expanses/create', [ManageExpensesController::class, 'create'])->name('manage-expanses.create');
    Route::post('/manage-expanses', [ManageExpensesController::class, 'store'])->name('manage-expanses.store');

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
