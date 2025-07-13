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

        // Waktu sekarang
        $now = Carbon::now();

        // Mulai dari 5 bulan lalu sampai bulan ini
        $startMonth = $now->copy()->subMonths(5)->startOfMonth();
        $endMonth = $now->copy()->endOfMonth();

        // Ambil data pemasukan selama 6 bulan terakhir untuk user ini
        $incomes = Income::where('user_id', $user->id)
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->get();

        // Group data pemasukan berdasarkan nama bulan (e.g. "Juni", "Juli", ...)
        // ================= Chart 1: Pemasukan per Bulan =================
    $monthlyIncome = $incomes->groupBy(fn($i) => $i->created_at->format('F'));
    $months = collect(CarbonPeriod::create($startMonth, '1 month', $endMonth))
        ->map(fn($d) => $d->format('F'));

    $incomeData = $months->map(function ($m) use ($monthlyIncome) {
        return $monthlyIncome->get($m, collect())->sum('amount');
    });

        // Buat array 6 bulan terakhir dalam urutan waktu
        $months = collect(CarbonPeriod::create($startMonth, '1 month', $endMonth))
            ->map(function ($date) {
                return $date->format('F');
            });


        // Total semua pemasukan
        $totalIncome = $incomes->sum('amount');

        // ================= Chart 2: Pemasukan per Kategori =================
        $groupedByCategory = $incomes->groupBy('category');

        $categoryData = [
            'labels' => $groupedByCategory->keys(),
            'data' => $groupedByCategory->map(fn($row) => $row->sum('amount'))->values(),
        ];

        return view('dashboard.kasir', [
            'months' => $months,
            'incomeData' => $incomeData->values()->all(),
            'totalIncome' => $totalIncome,
            'categoryData' => [
                'labels' => $categoryData['labels']->values()->all(),
                'data' => $categoryData['data']->all()
            ],
        ]);

        // Kirim ke view
        return view('dashboard.kasir', [
            'months' => $months,
            'incomeData' => $incomeData->values()->all(),
            'totalIncome' => $totalIncome
        ]);
    }
}
