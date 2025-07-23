<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;  
use App\Models\expense;
use App\Models\ExpensesCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $user = Auth::user();

    //     $sortField = $request->query('field', 'date');
    //     $sortDirection = $request->query('sort', 'asc') === 'asc' ? 'asc' : 'desc';

    //     $validSortFields = ['date', 'amount'];
    //     if (!in_array($sortField, $validSortFields)) {
    //         $sortField = 'date';
    //     }

    //     if ($user->role === 'manager') {
    //         $expensesQuery = Expense::where('user_id', $user->id);
    //     } elseif ($user->role === 'owner') {
    //         $managerIds = User::where('role', 'manager')->pluck('id');
    //         $expensesQuery = Expense::whereIn('user_id', $managerIds);
    //     } else {
    //         $expensesQuery = Expense::query()->whereRaw('0=1'); // kosongkan data untuk role lain
    //     }

    //     $expenses = $expensesQuery->orderBy($sortField, $sortDirection)
    //         ->with('user')
    //         ->paginate(5);

    //     $totalExpense = $expensesQuery->sum('amount');

    //     $expenseData = $expenses->groupBy(function ($expense) {
    //         return Carbon::parse($expense->date)->format('F Y');
    //     })->map(function ($grouped) {
    //         return $grouped->sum('amount');
    //     });

    //     $months = $expenseData->keys()->toArray();

    //     // Data Harian (5 bulan terakhir)
    //     $start = Carbon::now()->subMonths(5)->startOfMonth();
    //     $end = Carbon::now()->endOfMonth();

    //     $dailyExpenseRaw = (clone $expensesQuery)
    //         ->whereBetween('date', [$start, $end])
    //         ->select(DB::raw('DATE(date) as day'), DB::raw('SUM(amount) as total'))
    //         ->groupBy('day')
    //         ->orderBy('day')
    //         ->get();

    //     $dailyExpenseLabels = $dailyExpenseRaw->pluck('day');
    //     $dailyExpenseValues = $dailyExpenseRaw->pluck('total');

    //     $categoryData = (clone $expensesQuery)
    //         ->select('category', DB::raw('SUM(amount) as total'))
    //         ->groupBy('category')
    //         ->pluck('total', 'category');

    //     return view('expense.index', compact(
    //         'expenses',
    //         'totalExpense',
    //         'expenseData',
    //         'sortField',
    //         'sortDirection',
    //         'months',
    //         'categoryData',
    //         'dailyExpenseLabels',
    //         'dailyExpenseValues'
    //     ));
    // }

// Controller: index method
    public function index(Request $request)
    {
        $user = Auth::user();
        $items = ExpensesCategory::all();

        $sortField = $request->query('field', 'date');
        $sortDirection = $request->query('sort', 'asc') === 'asc' ? 'asc' : 'desc';
        $validSortFields = ['date', 'amount'];
        if (!in_array($sortField, $validSortFields)) {
            $sortField = 'date';
        }

        $managerIds = collect();
        $query = Expense::query();

        if ($user->role === 'manager') {
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'owner') {
            $managerIds = User::where('role', 'manager')->pluck('id');
            $query->whereIn('user_id', $managerIds);
        }

        // Filter berdasarkan hari, bulan, dan tahun
        if ($request->filled('day')) {
            $query->whereDay('date', $request->day); // Filter berdasarkan hari
        }

        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month); // Filter berdasarkan bulan
        }

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year); // Filter berdasarkan tahun
        }

        // Jika tidak ada filter hari, bulan, atau tahun, tampilkan data hari ini
        if (!$request->filled('day') && !$request->filled('month') && !$request->filled('year')) {
            $query->whereDate('date', Carbon::today());
        }

        $perPage = 10;

        // Paginate dengan $perPage
        $rawData = $query->orderBy($sortField, $sortDirection)->paginate($perPage);

        // Group data untuk ditampilkan
        $expensesGrouped = $rawData->getCollection()->groupBy('id_expenses');
        $groupedKeys = $expensesGrouped->keys();

        $totalExpensesPerGroup = $expensesGrouped->map(fn($group) => $group->sum('total_price'));
        $totalExpenses = $totalExpensesPerGroup->sum();

        $monthlyExpenses = $rawData->getCollection()->groupBy(fn($i) => Carbon::parse($i->date)->format('F'));
        $monthlyData = $monthlyExpenses->map(fn($group) => $group->groupBy('id_expenses')->map(fn($g) => $g->sum('total_price'))->sum())->values()->toArray();
        $months = $monthlyExpenses->keys()->toArray();

        // Hitung pengeluaran harian
        $dailyExpenses = $rawData->getCollection()->groupBy(fn($i) => Carbon::parse($i->date)->format('Y-m-d'));
        $dailyData = $dailyExpenses->map(fn($group) => $group->groupBy('id_expenses')->map(fn($g) => $g->sum('total_price'))->sum());

        // Format data harian
        $daily = [
            'labels' => $dailyData->keys()->toArray(),
            'values' => $dailyData->values()->toArray(),
        ];

        $availableYears = Expense::selectRaw('YEAR(date) as year')
            ->when($user->role === 'manager', fn($q) => $q->where('user_id', $user->id))
            ->when($user->role === 'owner', fn($q) => $q->whereIn('user_id', $managerIds))
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return view('expense.index', compact(
            'rawData',  // Data yang dipaginasi
            'expensesGrouped', // Data pengeluaran yang dikelompokkan
            'totalExpenses',
            'totalExpensesPerGroup',
            'monthlyData',
            'months',
            'daily',  // Kirim data harian ke view
            'availableYears',
            'items'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $expenses = ExpensesCategory::all();
        return view('expense.create', compact('expenses'));
    }

    public function store(Request $request)
    {
        try {
            // Validasi input berdasarkan kondisi
            $request->validate([
                'date' => 'required|array',
                'expenses_id' => 'required|array',
                'amount' => 'required|array',
                'description' => 'nullable|array',  // Validate description as string
                'quantity' => 'nullable|array',    // Validate quantity as numeric
                'price' => 'nullable|array',       // Validate price as numeric
            ]);

            // Membuat id_incomes di luar loop agar nilainya sama untuk semua entri
            $id_expenses = 'EXP' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);  // 4 random digits after 'INC'

            $totalAmount = 0;  // Variable for total amount that will be saved

            // Loop through each order to calculate total amount
            foreach ($request->expenses_id as $index => $expenses_id) {
                $expenses = ExpensesCategory::find($expenses_id);

                // Memastikan bahwa description adalah string
                $description = isset($request->description[$index]) ? (string) $request->description[$index] : 'No description';

                // Memastikan quantity dan price adalah angka
                $quantity = isset($request->quantity[$index]) ? (int) $request->quantity[$index] : 1; // default quantity to 1
                $price = isset($request->price[$index]) ? (float) $request->price[$index] : 0; // default price to 0

                // Pastikan quantity dan price valid
                if (!is_numeric($quantity) || !is_numeric($price)) {
                    throw new \Exception("Quantity dan Price harus berupa angka yang valid.");
                }

                // Log data untuk memastikan tipe data yang diterima
                Log::info('Expenses ID: ' . $expenses_id);
                Log::info('Quantity: ' . $quantity);
                Log::info('Price: ' . $price);
                Log::info('Description: ' . $description);

                // Calculate total price for each order
                $totalPrice = $price * $quantity;

                // Add to totalAmount
                $totalAmount += $totalPrice;
            }

            // Loop through each order again to store the data with the same amount
            foreach ($request->expenses_id as $index => $expenses_id) {
                $expenses = ExpensesCategory::find($expenses_id);

                // Memastikan bahwa description adalah string
                $description = isset($request->description[$index]) ? (string) $request->description[$index] : 'No description';

                // Memastikan quantity dan price adalah angka
                $quantity = isset($request->quantity[$index]) ? (int) $request->quantity[$index] : 1; // default quantity to 1
                $price = isset($request->price[$index]) ? (float) $request->price[$index] : 0; // default price to 0

                // Pastikan quantity dan price valid
                if (!is_numeric($quantity) || !is_numeric($price)) {
                    throw new \Exception("Quantity dan Price harus berupa angka yang valid.");
                }

                // Calculate total price for each order
                $totalPrice = $price * $quantity;

                // Store the income data in the database
                $income = new expense();
                $income->id_expenses = $id_expenses; // Use the same id_incomes for all records
                $income->user_id = auth()->id();
                $income->date = $request->date[0];  // Use the first entered date for all records
                $income->category = $expenses ? $expenses->category : 'Tip Karyawan'; // Save category from menu or 'Tip'
                $income->amount = $totalAmount;  // The same total amount for all entries
                $income->price = $price;
                $income->description = $description;  // Save the description (now it's guaranteed to be a string)
                $income->quantity = $quantity;
                $income->total_price = $totalPrice;  // Save total price for each item

                // Save the income data
                $income->save();
            }

            alert()->success('Success!', 'Data successfully added.');
            return redirect()->route('index.expense');
        } catch (\Exception $e) {
            Log::error('Error while saving data: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit($id_expenses)
    {
        // Cari grup berdasarkan id_incomes
        $expenses = expense::where('id_expenses', $id_expenses)->get();

        $items = ExpensesCategory::all(); // Ambil semua menu untuk pilihan
        return view('expense.edit', compact('expenses', 'items'));
    }

    public function update(Request $request, $id_expenses)
    {
        try {
            $request->validate([
                'date' => 'required|array',
                'expenses_id' => 'required|array',
                'amount' => 'required', // tidak array
                'description' => 'nullable|array',
                'quantity' => 'nullable|array',
                'price' => 'nullable|array',
            ]);

            $amount = $request->amount; // total harga akhir

            $expenses = expense::where('id_expenses', $id_expenses)->get();

            foreach ($expenses as $index => $expense) {
                $expense->quantity = $request->quantity[$index];
                $expense->price = $request->price[$index];
                $expense->description = $request->description[$index];
                $expense->total_price = $expense->price * $expense->quantity;
                $expense->date = $request->date[0];
                $expense->amount = $amount; // <- ini bagian penting
                $expense->save();
            }

            alert()->success('Success!', 'Data successfully updated.');
            return redirect()->route('index.expense');
        } catch (\Exception $e) {
            Log::error('Error while updating data: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();
        alert()->success('Berhasil', 'Data Berhasil Dihapus');
        return redirect()->route('index.expense');
    }
}
