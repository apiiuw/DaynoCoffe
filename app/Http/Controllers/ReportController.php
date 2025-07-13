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
    public function index()
    {
        // Ambil semua data tanpa filter user_id
        $incomeData = Income::all()->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y-m');
        });

        $expenseData = Expense::all()->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y-m');
        });

        $debtData = Debt::all()->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y-m');
        });

        $billData = Bill::all()->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y-m');
        });

        // Gabungkan semua bulan unik dari keempat sumber
        $allMonths = array_unique(array_merge(
            $incomeData->keys()->toArray(),
            $expenseData->keys()->toArray(),
            $debtData->keys()->toArray(),
            $billData->keys()->toArray()
        ));

        $reportData = [];
        $totalIncome = $totalExpense = $totalDebt = $totalBill = 0;

        foreach ($allMonths as $month) {
            $incomeSum = $incomeData->get($month, collect())->sum('amount');
            $expenseSum = $expenseData->get($month, collect())->sum('amount');
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

        return view('report.index', compact('reportData', 'totalIncome', 'totalExpense', 'totalDebt', 'totalBill'));
    }

    public function downloadPDF()
    {
        // Ambil semua data tanpa filter user_id
        $incomeData = Income::all()->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y-m');
        });

        $expenseData = Expense::all()->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y-m');
        });

        $debtData = Debt::all()->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y-m');
        });

        $billData = Bill::all()->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y-m');
        });

        $allMonths = array_unique(array_merge(
            $incomeData->keys()->toArray(),
            $expenseData->keys()->toArray(),
            $debtData->keys()->toArray(),
            $billData->keys()->toArray()
        ));

        $reportData = [];
        $totalIncome = $totalExpense = $totalDebt = $totalBill = 0;

        foreach ($allMonths as $month) {
            $incomeSum = $incomeData->get($month, collect())->sum('amount');
            $expenseSum = $expenseData->get($month, collect())->sum('amount');
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

        $pdf = PDF::loadView('report.pdf', compact('reportData', 'totalIncome', 'totalExpense', 'totalDebt', 'totalBill', 'currentDateTime'));

        return $pdf->download('laporan_keuangan.pdf');
    }
}
