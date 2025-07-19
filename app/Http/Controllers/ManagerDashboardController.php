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
    // public function index()
    // {
    //     $user = auth()->user();

    //     $now = Carbon::now();
    //     $startMonth = $now->copy()->subMonths(5)->startOfMonth();
    //     $endMonth = $now->copy()->endOfMonth();

    //     $months = collect(CarbonPeriod::create($startMonth, '1 month', $endMonth))
    //         ->map(fn($d) => $d->format('Y-m'));

    //     // Ambil data dari model
    //     $expenses = Expense::where('user_id', $user->id)
    //     ->whereBetween('date', [$startMonth, $endMonth])
    //     ->get()
    //     ->groupBy(fn($item) => \Carbon\Carbon::parse($item->date)->format('Y-m'));
    //     $bills = Bill::where('user_id', $user->id)
    //         ->whereBetween('created_at', [$startMonth, $endMonth])
    //         ->get();
    //     $debts = Debt::where('user_id', $user->id)
    //         ->whereBetween('created_at', [$startMonth, $endMonth])
    //         ->get();

    //     // Data per bulan
    //     $expenseData = $months->map(fn($month) => $expenses->get($month, collect())->sum('amount'));

    //     $billData = $months->map(function ($month) use ($bills) {
    //         return $bills->filter(fn($b) => $b->created_at->format('F') === $month)->sum('amount');
    //     });

    //     $debtData = $months->map(function ($month) use ($debts) {
    //         return $debts->filter(fn($d) => $d->created_at->format('F') === $month)->sum('amount');
    //     });
    //     $monthLabels = $months->map(fn($m) => Carbon::createFromFormat('Y-m', $m)->locale('id')->translatedFormat('F'));

    //     $totalExpense = Expense::sum('amount'); // tanpa filter user

    //     $expenseByCategory = DB::table('expenses')
    //     ->select('category', DB::raw('SUM(amount) as total'))
    //     ->groupBy('category')
    //     ->get();


    //     $expenseCategoryLabels = $expenseByCategory->pluck('category');
    //     $expenseCategoryValues = $expenseByCategory->pluck('total');

    //     return view('dashboard.manager', [
    //         'months' => $monthLabels,
    //         'expenseData' =>  $expenseData->values()->all(),
    //         'billData' => $billData->values()->all(),
    //         'debtData' => $debtData->values()->all(),
    //         'totalExpense' => $totalExpense,
    //         'totalIncome' => 0, // optional untuk pie chart agar tidak error
    //         'expenseCategoryLabels' => $expenseCategoryLabels,
    //         'expenseCategoryValues' => $expenseCategoryValues,
    //     ]);
    // }

    public function index()
    {
        $user = auth()->user();
        $now = Carbon::now();

        // Ambil 6 bulan terakhir berdasarkan kolom `date`
        $startMonth = $now->copy()->subMonths(5)->startOfMonth();
        $endMonth = $now->copy()->endOfMonth();

        // Ambil data income berdasarkan kolom `date`
        $expenses = expense::where('user_id', $user->id)
            ->whereBetween('date', [$startMonth, $endMonth])
            ->get();

        // ================= Chart 1: Pengeluaran per Bulan =================
        $monthlyExpenses = $expenses->groupBy(fn($i) => Carbon::parse($i->date)->format('F'));

        $months = collect(CarbonPeriod::create($startMonth, '1 month', $endMonth))
            ->map(fn($d) => $d->format('F'));

        $expensesData = $months->map(function ($monthName) use ($monthlyExpenses) {
            $expensesInMonth = $monthlyExpenses->get($monthName, collect());

            // Kelompokkan per id_incomes
            $groupedById = $expensesInMonth->groupBy('id_expenses');

            // Jumlahkan total_price per grup, lalu jumlahkan semua
            return $groupedById->map(fn($group) => $group->sum('total_price'))->sum();
        });

        // ================= Total Pengeluaran Keseluruhan =================
        $totalExpenses = $expenses
            ->groupBy('id_expenses')
            ->map(fn($group) => $group->sum('total_price'))
            ->sum();

        // ================= Chart 2: Pengeluaran per Kategori =================
        // Kelompokkan berdasarkan id_incomes dulu
        $groupedById = $expenses->groupBy('id_expenses');

        // Ambil semua kategori dan total per kategori dalam 1 grup id_incomes
        $groupedSums = $groupedById->flatMap(function ($group) {
            return $group->groupBy('category')->map(function ($items, $category) {
                return [
                    'category' => $category,
                    'total' => $items->sum('total_price')
                ];
            });
        });

        // Kelompokkan ulang berdasarkan kategori secara global
        $groupedByCategory = $groupedSums->groupBy('category')->map(fn($items) => collect($items)->sum('total'));

        $categoryData = [
            'labels' => $groupedByCategory->keys()->values(),
            'data' => $groupedByCategory->values(),
        ];

        // ================= Kirim ke View =================
        return view('dashboard.manager', [
            'months' => $months,
            'expensesData' => $expensesData->values()->all(),
            'totalExpenses' => $totalExpenses,
            'categoryData' => [
                'labels' => $categoryData['labels']->all(),
                'data' => $categoryData['data']->all()
            ],
        ]);
    }
}
