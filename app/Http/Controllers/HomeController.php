<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Income;
use App\Models\expense;
use App\Models\debt;
use App\Models\bill;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use App\Notifications\BillReminderNotification;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // public function index(Request $request)
    // {
    //     $role = auth()->user()->role;

    //     return match ($role) {
    //         'owner' => redirect('/dashboard/owner'),
    //         'kasir' => redirect('/dashboard/kasir'),
    //         'manager' => redirect('/dashboard/manager'),
    //         default => abort(403, 'Role tidak dikenali'),
    //     };
    //     $user = Auth::user();

    //     // Total ringkasan
    //     $totalIncome = Income::where('user_id', $user->id)->sum('amount');
    //     $totalExpense = Expense::where('user_id', $user->id)->sum('amount');
    //     $totalDebt = Debt::where('user_id', $user->id)->sum('amount');
    //     $totalBill = Bill::where('user_id', $user->id)->sum('amount');

    //     // Rentang waktu: 6 bulan terakhir
    //     $start = Carbon::now()->subMonths(5)->startOfMonth();
    //     $end = Carbon::now()->endOfMonth();
    //     $period = CarbonPeriod::create($start, '1 month', $end);

    //     // Label bulan
    //     $months = [];
    //     foreach ($period as $date) {
    //         $months[] = $date->format('F Y');
    //     }

    //     // Fungsi bantu isi data kosong
    //     $fillData = function ($data, $months) {
    //         $result = [];
    //         foreach ($months as $month) {
    //             $result[$month] = $data->get($month, 0);
    //         }
    //         return $result;
    //     };

    //     // ====== Pemasukan Bulanan ======
    //     $incomes = Income::where('user_id', $user->id)->get();
    //     $incomeData = $incomes->groupBy(function ($income) {
    //         return Carbon::parse($income->date)->format('F Y');
    //     })->map(function ($grouped) {
    //         return $grouped->sum('amount');
    //     });
    //     $incomeData = $fillData($incomeData, $months);

    //     // ====== Pengeluaran Bulanan ======
    //     $expenses = Expense::where('user_id', $user->id)->get();
    //     $expenseData = $expenses->groupBy(function ($expense) {
    //         return Carbon::parse($expense->date)->format('F Y');
    //     })->map(function ($grouped) {
    //         return $grouped->sum('amount');
    //     });
    //     $expenseData = $fillData($expenseData, $months);

    //     // ====== Hutang Bulanan ======
    //     $debts = Debt::where('user_id', $user->id)->get();
    //     $debtData = $debts->groupBy(function ($debt) {
    //         return Carbon::parse($debt->date)->format('F Y');
    //     })->map(function ($grouped) {
    //         return $grouped->sum('amount');
    //     });
    //     $debtData = $fillData($debtData, $months);

    //     // ====== Tagihan Bulanan ======
    //     $bills = Bill::where('user_id', $user->id)->get();
    //     $billData = $bills->groupBy(function ($bill) {
    //         return Carbon::parse($bill->date)->format('F Y');
    //     })->map(function ($grouped) {
    //         return $grouped->sum('amount');
    //     });
    //     $billData = $fillData($billData, $months);

    //     // ====== Pemasukan per Kategori ======
    //     $categoryData = Income::where('user_id', $user->id)
    //         ->select('category', DB::raw('SUM(amount) as total'))
    //         ->groupBy('category')
    //         ->get()
    //         ->pluck('total', 'category');

    //     // ====== Pengeluaran per Kategori ======
    //     $expenseByCategory = Expense::where('user_id', $user->id)
    //         ->select('category', DB::raw('SUM(amount) as total'))
    //         ->groupBy('category')
    //         ->get();

    //     $expenseCategoryLabels = $expenseByCategory->pluck('category');
    //     $expenseCategoryValues = $expenseByCategory->pluck('total');
    //     // ====== hutang perkategory ======
    //     $debtCategoryData = Debt::where('user_id', $user->id)
    //     ->select('category', DB::raw('SUM(amount) as total'))
    //     ->groupBy('category')
    //     ->get()
    //     ->pluck('total', 'category');


    //     // ====== Tagihahn per kategori ======

    //     $billCategoryData = Bill::where('user_id', $user->id)
    //     ->select('category', DB::raw('SUM(amount) as total'))
    //     ->groupBy('category')
    //     ->get()
    //     ->pluck('total', 'category');

    //     $today = Carbon::today();
    //     $dueDebts = Debt::where('user_id', $user->id)
    //                 ->whereDate('due_date', '<=', $today->copy()->addDays(7))
    //                 ->whereDate('due_date', '>=', $today)
    //                 ->get();

    //     $dueBills = Bill::where('user_id', $user->id)
    //                 ->whereDate('due_date', '<=', $today->copy()->addDays(7))
    //                 ->whereDate('due_date', '>=', $today)
    //                 ->get();

    //     // ====== Kirim ke view ======
    //     return view('home', compact(
    //         'totalIncome', 'totalExpense', 'totalDebt', 'totalBill',
    //         'incomeData', 'expenseData', 'debtData', 'billData', 'months',
    //         'categoryData', // income per kategori
    //         'expenseCategoryLabels', 'expenseCategoryValues', 'billCategoryData', 'debtCategoryData','dueDebts', 'dueBills' // expense per kategori
    //         ));
    //     }
    // }
