<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Bill;
use App\Models\Debt;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $now = Carbon::now();
        $startMonth = $now->copy()->subMonths(5)->startOfMonth();
        $endMonth = $now->copy()->endOfMonth();

        $months = collect(CarbonPeriod::create($startMonth, '1 month', $endMonth))
            ->map(fn($d) => $d->format('Y-m'));

        // Ambil data dari model
        $expenses = Expense::where('user_id', $user->id)
        ->whereBetween('date', [$startMonth, $endMonth])
        ->get()
        ->groupBy(fn($item) => \Carbon\Carbon::parse($item->date)->format('Y-m'));
        $bills = Bill::where('user_id', $user->id)
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->get();
        $debts = Debt::where('user_id', $user->id)
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->get();

        // Data per bulan
        $expenseData = $months->map(fn($month) => $expenses->get($month, collect())->sum('amount'));

        $billData = $months->map(function ($month) use ($bills) {
            return $bills->filter(fn($b) => $b->created_at->format('F') === $month)->sum('amount');
        });

        $debtData = $months->map(function ($month) use ($debts) {
            return $debts->filter(fn($d) => $d->created_at->format('F') === $month)->sum('amount');
        });
        $monthLabels = $months->map(fn($m) => Carbon::createFromFormat('Y-m', $m)->locale('id')->translatedFormat('F'));

        $totalExpense = Expense::sum('amount'); // tanpa filter user

    $expenseByCategory = DB::table('expenses')
    ->select('category', DB::raw('SUM(amount) as total'))
    ->groupBy('category')
    ->get();


    $expenseCategoryLabels = $expenseByCategory->pluck('category');
    $expenseCategoryValues = $expenseByCategory->pluck('total');

        return view('dashboard.manager', [
            'months' => $monthLabels,
            'expenseData' =>  $expenseData->values()->all(),
            'billData' => $billData->values()->all(),
            'debtData' => $debtData->values()->all(),
            'totalExpense' => $totalExpense,
            'totalIncome' => 0, // optional untuk pie chart agar tidak error
            'expenseCategoryLabels' => $expenseCategoryLabels,
            'expenseCategoryValues' => $expenseCategoryValues,
        ]);
    }
}
