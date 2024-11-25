<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\expense;
use App\Models\debt;
use App\Models\bill;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use PDF;


class ReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Fetch data grouped by month
        $incomeData = Income::where('user_id', $user->id)
        ->get()
        ->groupBy(function ($income) {
            return Carbon::parse($income->date)->format('Y-m');
        });

        $expenseData = Expense::where('user_id', $user->id)
            ->get()
            ->groupBy(function ($expense) {
                return Carbon::parse($expense->date)->format('Y-m');
            });

        $debtData = Debt::where('user_id', $user->id)
            ->get()
            ->groupBy(function ($debt) {
                return Carbon::parse($debt->date)->format('Y-m');
            });

        $billData = Bill::where('user_id', $user->id)
            ->get()
            ->groupBy(function ($bill) {
                return Carbon::parse($bill->date)->format('Y-m');
            });

        // Calculate totals for each month
        $reportData = [];
        $allMonths = array_unique(array_merge(
            $incomeData->keys()->toArray(),
            $expenseData->keys()->toArray(),
            $debtData->keys()->toArray(),
            $billData->keys()->toArray()
        ));

        $totalIncome = 0;
        $totalExpense = 0;
        $totalDebt = 0;
        $totalBill = 0;

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

        ksort($reportData); // Sort by month

        return view('report.index', compact('reportData', 'totalIncome', 'totalExpense', 'totalDebt', 'totalBill'));
    
    }

    public function downloadPDF()
    {
        $user = Auth::user();

        // Fetch data grouped by month
        $incomeData = Income::where('user_id', $user->id)
            ->get()
            ->groupBy(function ($income) {
                return Carbon::parse($income->date)->format('Y-m');
            });

        $expenseData = Expense::where('user_id', $user->id)
            ->get()
            ->groupBy(function ($expense) {
                return Carbon::parse($expense->date)->format('Y-m');
            });

        $debtData = Debt::where('user_id', $user->id)
            ->get()
            ->groupBy(function ($debt) {
                return Carbon::parse($debt->date)->format('Y-m');
            });

        $billData = Bill::where('user_id', $user->id)
            ->get()
            ->groupBy(function ($bill) {
                return Carbon::parse($bill->date)->format('Y-m');
            });

        // Calculate totals for each month
        $reportData = [];
        $allMonths = array_unique(array_merge(
            $incomeData->keys()->toArray(),
            $expenseData->keys()->toArray(),
            $debtData->keys()->toArray(),
            $billData->keys()->toArray()
        ));

        $totalIncome = 0;
        $totalExpense = 0;
        $totalDebt = 0;
        $totalBill = 0;

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

        ksort($reportData); // Sort by month

        // Get current date and time
        $currentDateTime = Carbon::now()->format('d F Y H:i');

        // Generate PDF
        $pdf = PDF::loadView('report.pdf', compact('reportData', 'totalIncome', 'totalExpense', 'totalDebt', 'totalBill', 'currentDateTime'));

        return $pdf->download('laporan_keuangan.pdf');
    }
}
