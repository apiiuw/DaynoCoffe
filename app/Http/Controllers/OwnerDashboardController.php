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
public function index(Request $request)
{
    $now = Carbon::now();
    $startMonth = $now->copy()->subMonths(5)->startOfMonth();
    $endMonth = $now->copy()->endOfMonth();

    $months = collect(CarbonPeriod::create($startMonth, '1 month', $endMonth))
        ->map(fn($d) => $d->format('Y-m'));

    // ===================== FILTER PERIODE =====================
    $day = $request->input('day');
    $month = $request->input('month');
    $year = $request->input('year');

    $incomeYears = DB::table('incomes')->selectRaw('YEAR(date) as year')->distinct()->pluck('year');
    $expanseYears = DB::table('expenses')->selectRaw('YEAR(date) as year')->distinct()->pluck('year');
    $billYears    = DB::table('bills')->selectRaw('YEAR(date) as year')->distinct()->pluck('year');
    $debtYears    = DB::table('debts')->selectRaw('YEAR(date) as year')->distinct()->pluck('year');

    // Gabungkan semua dan ambil nilai unik
    $availableYears = collect()
        ->merge($incomeYears)
        ->merge($expanseYears)
        ->merge($billYears)
        ->merge($debtYears)
        ->unique()
        ->sortDesc()
        ->values(); // Reset index

    $incomeQuery = Income::query();
    $expenseQuery = Expense::query();
    $billQuery = Bill::query();
    $debtQuery = Debt::query();

    if ($day || $month || $year) {
        if ($day && $month && $year) {
            $date = Carbon::createFromDate($year, $month, $day);
            $incomeQuery->whereDate('date', $date);
            $expenseQuery->whereDate('date', $date);
            $billQuery->whereDate('date', $date);
            $debtQuery->whereDate('date', $date);
        } elseif ($month && $year) {
            $incomeQuery->whereMonth('date', $month)->whereYear('date', $year);
            $expenseQuery->whereMonth('date', $month)->whereYear('date', $year);
            $billQuery->whereMonth('date', $month)->whereYear('date', $year);
            $debtQuery->whereMonth('date', $month)->whereYear('date', $year);
        } elseif ($month) {
            $incomeQuery->whereMonth('date', $month);
            $expenseQuery->whereMonth('date', $month);
            $billQuery->whereMonth('date', $month);
            $debtQuery->whereMonth('date', $month);
        } elseif ($year) {
            $incomeQuery->whereYear('date', $year);
            $expenseQuery->whereYear('date', $year);
            $billQuery->whereYear('date', $year);
            $debtQuery->whereYear('date', $year);
        }
    }

    $incomes = $incomeQuery->get();
    $expenses = $expenseQuery->get();
    $bills = $billQuery->get();
    $debts = $debtQuery->get();

    // ===================== GROUP & CHART DATA =====================
    $incomesGrouped = $incomes->groupBy(fn($item) => Carbon::parse($item->date)->format('Y-m'));
    $expensesGrouped = $expenses->groupBy(fn($item) => Carbon::parse($item->date)->format('Y-m'));
    $billsGrouped = $bills->groupBy(fn($item) => Carbon::parse($item->date)->format('Y-m'));
    $debtsGrouped = $debts->groupBy(fn($item) => Carbon::parse($item->date)->format('Y-m'));

    $monthLabels = $months->map(fn($m) => Carbon::createFromFormat('Y-m', $m)->locale('id')->translatedFormat('F'));

    $expenseData = $months->map(function ($month) use ($expensesGrouped) {
        $group = $expensesGrouped->get($month, collect());
        return $group->groupBy('id_expenses')->map(fn($g) => $g->first()->amount)->sum();
    });

    $incomeData = $months->map(function ($month) use ($incomesGrouped) {
        $group = $incomesGrouped->get($month, collect());
        return $group->groupBy('id_incomes')->map(fn($g) => $g->first()->amount)->sum();
    });

    $billData = $months->map(fn($month) => $billsGrouped->get($month, collect())->sum('amount'));
    $debtData = $months->map(fn($month) => $debtsGrouped->get($month, collect())->sum('amount'));

    $totalIncome = $incomes->groupBy('id_incomes')->map(fn($g) => $g->first()->amount)->sum();
    $totalExpense = $expenses->groupBy('id_expenses')->map(fn($g) => $g->first()->amount)->sum();
    $totalBill = $bills->sum('amount');
    $totalDebt = $debts->sum('amount');

    $categoryData = Income::select('category', DB::raw('SUM(amount) as total'))->groupBy('category')->get()->pluck('total', 'category');

    $expenseByCategory = Expense::select('category', DB::raw('SUM(amount) as total'))->groupBy('category')->get();
    $expenseCategoryLabels = $expenseByCategory->pluck('category');
    $expenseCategoryValues = $expenseByCategory->pluck('total');

    $billCategoryData = Bill::select('category', DB::raw('SUM(amount) as total'))->groupBy('category')->get()->pluck('total', 'category');
    $debtCategoryData = Debt::select('category', DB::raw('SUM(amount) as total'))->groupBy('category')->get()->pluck('total', 'category');

    $user = Auth::user();
    $dueStart = Carbon::today();
    $dueEnd = Carbon::today()->addDays(7);

    $dueDebts = Debt::where('user_id', $user->id)->whereBetween('due_date', [$dueStart, $dueEnd])->get();
    $dueBills = Bill::where('user_id', $user->id)->whereBetween('due_date', [$dueStart, $dueEnd])->get();

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
        'bills' => $bills,
        'availableYears' => $availableYears,
    ]);
}

}
