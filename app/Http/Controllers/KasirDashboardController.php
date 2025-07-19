<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
 
class KasirDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $now = Carbon::now();

        // Ambil 6 bulan terakhir berdasarkan kolom `date`
        $startMonth = $now->copy()->subMonths(5)->startOfMonth();
        $endMonth = $now->copy()->endOfMonth();

        // Ambil data income berdasarkan kolom `date`
        $incomes = Income::where('user_id', $user->id)
            ->whereBetween('date', [$startMonth, $endMonth])
            ->get();

        // ================= Chart 1: Pemasukan per Bulan =================
        $monthlyIncome = $incomes->groupBy(fn($i) => Carbon::parse($i->date)->format('F'));

        $months = collect(CarbonPeriod::create($startMonth, '1 month', $endMonth))
            ->map(fn($d) => $d->format('F'));

        $incomeData = $months->map(function ($monthName) use ($monthlyIncome) {
            $incomesInMonth = $monthlyIncome->get($monthName, collect());

            // Kelompokkan per id_incomes
            $groupedById = $incomesInMonth->groupBy('id_incomes');

            // Jumlahkan total_price per grup, lalu jumlahkan semua
            return $groupedById->map(fn($group) => $group->sum('total_price'))->sum();
        });

        // ================= Total Pemasukan Keseluruhan =================
        $totalIncome = $incomes
            ->groupBy('id_incomes')
            ->map(fn($group) => $group->sum('total_price'))
            ->sum();

        // ================= Chart 2: Pemasukan per Kategori =================
        // Kelompokkan berdasarkan id_incomes dulu
        $groupedById = $incomes->groupBy('id_incomes');

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
        return view('dashboard.kasir', [
            'months' => $months,
            'incomeData' => $incomeData->values()->all(),
            'totalIncome' => $totalIncome,
            'categoryData' => [
                'labels' => $categoryData['labels']->all(),
                'data' => $categoryData['data']->all()
            ],
        ]);
    }

}
