<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;  
use App\Models\expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class ExpenseController extends Controller
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

    if ($user->role === 'manager') {
        $expensesQuery = Expense::where('user_id', $user->id);
    } elseif ($user->role === 'owner') {
        $managerIds = User::where('role', 'manager')->pluck('id');
        $expensesQuery = Expense::whereIn('user_id', $managerIds);
    } else {
        $expensesQuery = Expense::query()->whereRaw('0=1'); // kosongkan data untuk role lain
    }

    $expenses = $expensesQuery->orderBy($sortField, $sortDirection)
        ->with('user')
        ->paginate(5);

    $totalExpense = $expensesQuery->sum('amount');

    $expenseData = $expenses->groupBy(function ($expense) {
        return Carbon::parse($expense->date)->format('F Y');
    })->map(function ($grouped) {
        return $grouped->sum('amount');
    });

    $months = $expenseData->keys()->toArray();

    // Data Harian (5 bulan terakhir)
    $start = Carbon::now()->subMonths(5)->startOfMonth();
    $end = Carbon::now()->endOfMonth();

    $dailyExpenseRaw = (clone $expensesQuery)
        ->whereBetween('date', [$start, $end])
        ->select(DB::raw('DATE(date) as day'), DB::raw('SUM(amount) as total'))
        ->groupBy('day')
        ->orderBy('day')
        ->get();

    $dailyExpenseLabels = $dailyExpenseRaw->pluck('day');
    $dailyExpenseValues = $dailyExpenseRaw->pluck('total');

    $categoryData = (clone $expensesQuery)
        ->select('category', DB::raw('SUM(amount) as total'))
        ->groupBy('category')
        ->pluck('total', 'category');

    return view('expense.index', compact(
        'expenses',
        'totalExpense',
        'expenseData',
        'sortField',
        'sortDirection',
        'months',
        'categoryData',
        'dailyExpenseLabels',
        'dailyExpenseValues'
    ));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expense.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi input
        $request->validate([
    'date' => 'required|date',
    'category' => 'required|string',
    'amount' => 'required|numeric',
    'description' => 'nullable|string',
]);

$user = Auth::user();

// Buat objek pengeluaran (Expense)
$expense = new Expense();
$expense->user_id    = $user->id;
$expense->date       = $request->input('date');          // ← penting, karena 'date' wajib di DB
$expense->category   = $request->input('category');      // ← pastikan nama kolom di DB adalah 'category', bukan 'category_id'
$expense->amount     = $request->input('amount');
$expense->description = $request->input('description');
$expense->save();


        // Simpan pengeluaran baru ke database
        $expense->save();

        alert()->success('Berhasil!', 'Data Berhasil Ditambah');
         return redirect()->route('index.expense')->with('success', 'Pengeluaran berhasil ditambahkan!');
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
        $expense = Expense::findOrFail($id);
        return view('expense.edit', compact('expense'));
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

        $expense = Expense::findOrFail($id);
        $expense->date = $request->date;
        $expense->category = $request->category;
        $expense->amount = $request->amount;
        $expense->description = $request->description;
        $expense->save();

        alert()->success('Berhasil!', 'Data Berhasil Diperbarui');
        return redirect()->route('index.expense');
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
