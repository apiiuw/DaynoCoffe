<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\income;
use App\Models\expense;
use App\Models\Debt;
use App\Models\Bill;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use App\Notifications\BillReminderNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $startMonth = $now->copy()->subMonths(5)->startOfMonth();
        $endMonth = $now->copy()->endOfMonth();

        $months = collect(CarbonPeriod::create($startMonth, '1 month', $endMonth))
            ->map(fn($d) => $d->format('Y-m'));

        // Ambil semua data (tanpa filter user)
        $incomes = Income::whereBetween('date', [$startMonth, $endMonth])->get();
        $expenses = Expense::whereBetween('date', [$startMonth, $endMonth])->get();
        $bills = Bill::whereBetween('date', [$startMonth, $endMonth])->get();
        $debts = Debt::whereBetween('date', [$startMonth, $endMonth])->get();

        // Grouping per bulan
        $incomesGrouped = $incomes->groupBy(fn($item) => Carbon::parse($item->date)->format('Y-m'));
        $expensesGrouped = $expenses->groupBy(fn($item) => Carbon::parse($item->date)->format('Y-m'));
        $billsGrouped = $bills->groupBy(fn($item) => Carbon::parse($item->date)->format('Y-m'));
        $debtsGrouped = $debts->groupBy(fn($item) => Carbon::parse($item->date)->format('Y-m'));

        // Format label bulan untuk chart
        $monthLabels = $months->map(fn($m) => Carbon::createFromFormat('Y-m', $m)->locale('id')->translatedFormat('F'));

        // Data chart per bulan - Menghitung pemasukan berdasarkan id_incomes (menghitung amount unik)
        $expenseData = $months->map(function ($month) use ($expensesGrouped) {
            $expensesInMonth = $expensesGrouped->get($month, collect());

            // Kelompokkan per id_expenses yang unik
            $groupedById = $expensesInMonth->groupBy('id_expenses');

            // Jumlahkan total_amount yang unik per id_expenses
            return $groupedById->map(fn($group) => $group->first()->amount)->sum();
        });

                $incomeData = $months->map(function ($month) use ($expensesGrouped) {
            $expensesInMonth = $expensesGrouped->get($month, collect());

            // Kelompokkan per id_expenses yang unik
            $groupedById = $expensesInMonth->groupBy('id_expenses');

            // Jumlahkan total_amount yang unik per id_expenses
            return $groupedById->map(fn($group) => $group->first()->amount)->sum();
        });

        // Data chart per bulan lainnya
        $billData = $months->map(fn($month) => $billsGrouped->get($month, collect())->sum('amount'));
        $debtData = $months->map(fn($month) => $debtsGrouped->get($month, collect())->sum('amount'));

        // ================= Total Keseluruhan: Pengelompokan Berdasarkan id_incomes =================
        $totalIncome = $incomes
            ->groupBy('id_incomes') // Kelompokkan berdasarkan id_incomes
            ->map(fn($group) => $group->first()->amount) // Ambil hanya 1 value amount per id_incomes
            ->sum(); // Totalkan seluruh jumlah

        // ================= Total Keseluruhan: Pengelompokan Berdasarkan id_expenses =================
        $totalExpense = $expenses
            ->groupBy('id_expenses') // Kelompokkan berdasarkan id_incomes
            ->map(fn($group) => $group->first()->amount) // Ambil hanya 1 value amount per id_incomes
            ->sum(); // Totalkan seluruh jumlah

        // ================= Total Pengeluaran, Tagihan, Hutang (Tanpa id_incomes) =================
        $totalBill = $bills->sum('amount'); // Total tagihan tanpa pengelompokan berdasarkan id_incomes
        $totalDebt = $debts->sum('amount'); // Total hutang tanpa pengelompokan berdasarkan id_incomes

        // Per kategori
        $categoryData = Income::select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')->get()->pluck('total', 'category');

        $expenseByCategory = expense::select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')->get();

        $expenseCategoryLabels = $expenseByCategory->pluck('category');
        $expenseCategoryValues = $expenseByCategory->pluck('total');

        $billCategoryData = Bill::select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')->get()->pluck('total', 'category');

        $debtCategoryData = Debt::select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')->get()->pluck('total', 'category');

        $today = Carbon::today();
        // Cek role user
        $user = Auth::user();
        $dueStart = Carbon::today();
        $dueEnd = Carbon::today()->addDays(7);

        $dueDebts = Debt::where('user_id', $user->id)
            ->whereBetween('due_date', [$dueStart, $dueEnd])
            ->get();

        $dueBills = Bill::where('user_id', $user->id)
            ->whereBetween('due_date', [$dueStart, $dueEnd])
            ->get();

        // Ambil daftar tagihan untuk tabel bawah
        $bills = Bill::where('user_id', $user->id)->paginate(10);

        return view('dashboard.owner', [
            'months' => $monthLabels,
            'incomeData' => $incomeData->values()->all(),
            'expenseData' => $expenseData->values()->all(),
            'billData' => $billData->values()->all(),
            'debtData' => $debtData->values()->all(),

            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'totalBill' => $totalBill,
            'totalDebt' => $totalDebt,

            'categoryData' => $categoryData,
            'expenseCategoryLabels' => $expenseCategoryLabels,
            'expenseCategoryValues' => $expenseCategoryValues,
            'billCategoryData' => $billCategoryData,
            'debtCategoryData' => $debtCategoryData,
            'dueDebts' => $dueDebts,
            'dueBills' => $dueBills,
            'bills' => $bills
        ]);
    }

}
