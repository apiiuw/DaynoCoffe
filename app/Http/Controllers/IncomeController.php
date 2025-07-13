<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Menu;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $sortField = $request->query('field', 'date');
        $sortDirection = $request->query('sort', 'asc') === 'asc' ? 'asc' : 'desc';
        $validSortFields = ['date', 'amount'];

        if (!in_array($sortField, $validSortFields)) {
            $sortField = 'date';
        }

        $kasirIds = collect(); // inisialisasi untuk owner

        // Setup query builder
        $query = Income::query();

        if ($user->role === 'kasir') {
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'owner') {
            $kasirIds = User::where('role', 'kasir')->pluck('id');
            $query->whereIn('user_id', $kasirIds);
        } else {
            // untuk role lain, kosongkan semua data
            $incomes = collect();
            $totalIncome = 0;
            $incomeData = collect();
            $months = [];
            $daily = [];
            $categoryData = collect();
            $availableYears = collect();

            return view('income.index', compact(
                'incomes',
                'totalIncome',
                'incomeData',
                'sortField',
                'sortDirection',
                'months',
                'daily',
                'categoryData',
                'availableYears'
            ));
        }

        // Filter bulan dan tahun
        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        // Total income setelah filter
        $totalIncome = $query->sum('amount');

        // Ambil pemasukan untuk tabel
        $incomes = $query->orderBy($sortField, $sortDirection)
            ->with('user')
            ->paginate(10)
            ->withQueryString();

        // Data kategori
        $categoryData = (clone $query)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->pluck('total', 'category');

        // Untuk grafik harian
        $dailyIncomes = (clone $query)->orderBy('date')->get()->groupBy(function ($income) {
            return Carbon::parse($income->date)->format('Y-m-d');
        });

        $daily = [
            'labels' => $dailyIncomes->keys()->toArray(),
            'values' => $dailyIncomes->map(function ($group) {
                return $group->sum('amount');
            })->values()->toArray()
        ];

        // Data chart bulanan
        $incomeData = $incomes->groupBy(function ($income) {
            return Carbon::parse($income->date)->format('F Y');
        })->map(function ($groupedIncomes) {
            return $groupedIncomes->sum('amount');
        });

        $months = $incomeData->keys()->toArray();

        // Dropdown tahun
        $availableYears = Income::selectRaw('YEAR(date) as year')
            ->when($user->role === 'kasir', function ($q) use ($user) {
                return $q->where('user_id', $user->id);
            })
            ->when($user->role === 'owner', function ($q) use ($kasirIds) {
                return $q->whereIn('user_id', $kasirIds);
            })
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return view('income.index', compact(
            'incomes',
            'totalIncome',
            'incomeData',
            'sortField',
            'sortDirection',
            'months',
            'daily',
            'categoryData',
            'availableYears'
        ));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Menu::where('availability', 'Tersedia')
                        ->select('category')
                        ->distinct()
                        ->orderBy('category')
                        ->pluck('category');

        $menus = Menu::where('availability', 'Tersedia')->get(); // hanya yang tersedia

        return view('income.create', compact('categories', 'menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'category' => 'required|string',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'quantity' => 'nullable|numeric',
        ]);

        $income = new Income();
        $income->user_id = auth()->id();
        $income->date = $request->date;
        $income->category = $request->category;
        $income->amount = $request->amount;
        $income->description = $request->description;
        $income->save();

        alert()->success('Berhasil!', 'Data Berhasil Ditambah');
        return redirect()->route('index.income');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $income = Income::findOrFail($id);
        return view('income.edit', compact('income'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'date' => 'required|date',
            'category' => 'required|string',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $income = Income::findOrFail($id);
        $income->date = $request->date;
        $income->category = $request->category;
        $income->amount = $request->amount;
        $income->description = $request->description;
        $income->save();

        alert()->success('Berhasil!', 'Data Berhasil Diperbarui');
        return redirect()->route('index.income');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $income = Income::findOrFail($id);
        $income->delete();
        alert()->success('Berhasil', 'Data Berhasil Dihapus');
        return redirect()->route('index.income');
    }

    public function dailyIncomeChart(Request $request)
    {
        $userId = auth()->id(); // Mengambil ID pengguna yang sedang login
        $month = $request->input('month', date('m')); // Bulan default adalah bulan saat ini
        $year = $request->input('year', date('Y')); // Tahun default adalah tahun saat ini

        // Query untuk mengambil data
        $dailyIncomes = DB::table('incomes')
            ->select(DB::raw('DATE(date) as date'), DB::raw('SUM(amount) as total_amount'))
            ->where('user_id', $userId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Siapkan data untuk Chart.js
        $dates = $dailyIncomes->pluck('date'); // Tanggal
        $amounts = $dailyIncomes->pluck('total_amount'); // Total pemasukan harian

        // Kirim ke view
        return view('daily_chart', compact('dates', 'amounts', 'month', 'year'));
    }
}
