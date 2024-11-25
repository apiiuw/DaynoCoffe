<?php

namespace App\Http\Controllers;

use App\Models\expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        // Default sort field and direction
        $sortField = $request->query('field', 'date');
        $sortDirection = $request->query('sort', 'asc') == 'asc' ? 'asc' : 'desc';

        // Validate sort field to prevent SQL injection
        $validSortFields = ['date', 'amount'];
        if (!in_array($sortField, $validSortFields)) {
            $sortField = 'date';
        }

        // Retrieve sorted expenses
        $expenses = Expense::where('user_id', $user->id)
            ->orderBy($sortField, $sortDirection)
            ->paginate(5);

        // Menghitung total pengeluaran
        $totalExpense = Expense::where('user_id', $user->id)->sum('amount');

        $title = 'Delete Data!';
        $text = "Anda yakin ingin menghapus?";
        confirmDelete($title, $text);

        // Group expense data by month and calculate total expense for each month
        $expenseData = $expenses->groupBy(function ($expense) {
            return Carbon::parse($expense->date)->format('F Y');
        })->map(function ($groupedExpenses) {
            return $groupedExpenses->sum('amount');
        });

        // Months for chart labels
        $months = $expenseData->keys()->toArray();

        return view('expense.index', compact('expenses', 'totalExpense', 'expenseData', 'sortField', 'sortDirection', "months"));
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

        // buat objek pengeluaran
        $expense = new Expense();
        $expense->user_id = auth()->id();
        $expense->date = $request->input('date');
        $expense->category = $request->input('category');
        $expense->amount = $request->input('amount');
        $expense->description = $request->input('description');

        // Simpan pengeluaran baru ke database
        $expense->save();

        alert()->success('Berhasil!', 'Data Berhasil Ditambah');
        return redirect()->route('index.expense');
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
