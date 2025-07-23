<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Debt;
use App\Models\Bill;
use Carbon\Carbon;
use PDF;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->input('month');
        $selectedYear = $request->input('year');

        // Mulai query builder untuk semua tabel
        $incomeQuery = Income::query();
        $expenseQuery = Expense::query();
        $debtQuery = Debt::query();
        $billQuery = Bill::query();

        // Terapkan filter bulan dan tahun jika disetel
        if ($selectedYear) {
            $incomeQuery->whereYear('date', $selectedYear);
            $expenseQuery->whereYear('date', $selectedYear);
            $debtQuery->whereYear('date', $selectedYear);
            $billQuery->whereYear('date', $selectedYear);
        }

        if ($selectedMonth) {
            $incomeQuery->whereMonth('date', $selectedMonth);
            $expenseQuery->whereMonth('date', $selectedMonth);
            $debtQuery->whereMonth('date', $selectedMonth);
            $billQuery->whereMonth('date', $selectedMonth);
        }

        // Ambil data yang sudah difilter
        $incomeData = $incomeQuery->get()->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y-m');
        });

        $expenseData = $expenseQuery->get()->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y-m');
        });

        $debtData = $debtQuery->get()->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y-m');
        });

        $billData = $billQuery->get()->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y-m');
        });

        // Gabungkan semua bulan unik
        $allMonths = array_unique(array_merge(
            $incomeData->keys()->toArray(),
            $expenseData->keys()->toArray(),
            $debtData->keys()->toArray(),
            $billData->keys()->toArray()
        ));

        $reportData = [];
        $totalIncome = $totalExpense = $totalDebt = $totalBill = 0;

        foreach ($allMonths as $month) {
            $incomesInMonth = $incomeData->get($month, collect());
            $groupedByIdIncomes = $incomesInMonth->groupBy('id_incomes');
            $incomeSum = $groupedByIdIncomes->map(fn($group) => $group->first()->amount)->sum();

            $expensesInMonth = $expenseData->get($month, collect());
            $groupedByIdExpenses = $expensesInMonth->groupBy('id_expenses');
            $expenseSum = $groupedByIdExpenses->map(fn($group) => $group->first()->amount)->sum();

            $debtSum = $debtData->get($month, collect())->sum('amount');
            $billSum = $billData->get($month, collect())->sum('amount');

            $reportData[$month] = [
                'income' => $incomeSum,
                'expense' => $expenseSum,
                'debt' => $debtSum,
                'bill' => $billSum
            ];

            $totalIncome += $incomeSum;
            $totalExpense += $expenseSum;
            $totalDebt += $debtSum;
            $totalBill += $billSum;
        }

        ksort($reportData);

        $profitOrLoss = $totalIncome - $totalExpense;
        $profit = $profitOrLoss > 0 ? $profitOrLoss : 0;
        $loss   = $profitOrLoss < 0 ? abs($profitOrLoss) : 0;

        // Ambil daftar tahun unik untuk pilihan tahun di view
        $availableYears = Income::selectRaw('YEAR(date) as year')->distinct()->pluck('year')->sortDesc();

        return view('report.index', compact(
            'reportData',
            'totalIncome',
            'totalExpense',
            'totalDebt',
            'totalBill',
            'profit',
            'loss',
            'availableYears'
        ));
    }

    public function downloadPDF(Request $request)
    {
        $selectedMonth = $request->input('month');
        $selectedYear = $request->input('year');

        $incomeQuery = Income::query();
        $expenseQuery = Expense::query();
        $debtQuery = Debt::query();
        $billQuery = Bill::query();

        if ($selectedYear) {
            $incomeQuery->whereYear('date', $selectedYear);
            $expenseQuery->whereYear('date', $selectedYear);
            $debtQuery->whereYear('date', $selectedYear);
            $billQuery->whereYear('date', $selectedYear);
        }

        if ($selectedMonth) {
            $incomeQuery->whereMonth('date', $selectedMonth);
            $expenseQuery->whereMonth('date', $selectedMonth);
            $debtQuery->whereMonth('date', $selectedMonth);
            $billQuery->whereMonth('date', $selectedMonth);
        }

        $incomeData = $incomeQuery->get()->groupBy(fn($item) => Carbon::parse($item->date)->format('Y-m'));
        $expenseData = $expenseQuery->get()->groupBy(fn($item) => Carbon::parse($item->date)->format('Y-m'));
        $debtData = $debtQuery->get()->groupBy(fn($item) => Carbon::parse($item->date)->format('Y-m'));
        $billData = $billQuery->get()->groupBy(fn($item) => Carbon::parse($item->date)->format('Y-m'));

        $allMonths = array_unique(array_merge(
            $incomeData->keys()->toArray(),
            $expenseData->keys()->toArray(),
            $debtData->keys()->toArray(),
            $billData->keys()->toArray()
        ));

        $reportData = [];
        $totalIncome = $totalExpense = $totalDebt = $totalBill = 0;

        foreach ($allMonths as $month) {
            $incomesInMonth = $incomeData->get($month, collect());
            $groupedByIdIncomes = $incomesInMonth->groupBy('id_incomes');
            $incomeSum = $groupedByIdIncomes->map(fn($group) => $group->first()->amount)->sum();

            $expensesInMonth = $expenseData->get($month, collect());
            $groupedByIdExpenses = $expensesInMonth->groupBy('id_expenses');
            $expenseSum = $groupedByIdExpenses->map(fn($group) => $group->first()->amount)->sum();

            $debtSum = $debtData->get($month, collect())->sum('amount');
            $billSum = $billData->get($month, collect())->sum('amount');

            $reportData[$month] = [
                'income' => $incomeSum,
                'expense' => $expenseSum,
                'debt' => $debtSum,
                'bill' => $billSum
            ];

            $totalIncome += $incomeSum;
            $totalExpense += $expenseSum;
            $totalDebt += $debtSum;
            $totalBill += $billSum;
        }

        ksort($reportData);

        $currentDateTime = Carbon::now()->format('d F Y H:i');
        $profitOrLoss = $totalIncome - $totalExpense;
        $profit = $profitOrLoss > 0 ? $profitOrLoss : 0;
        $loss = $profitOrLoss < 0 ? abs($profitOrLoss) : 0;

        $pdf = PDF::loadView('report.pdf', compact(
            'reportData', 'totalIncome', 'totalExpense', 'totalDebt',
            'totalBill', 'currentDateTime', 'profit', 'loss',
            'selectedMonth', 'selectedYear' 
        ));

        return $pdf->download('laporan_keuangan.pdf');
    }


}
