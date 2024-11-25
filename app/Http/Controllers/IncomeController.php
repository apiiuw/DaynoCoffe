<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
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

        // Retrieve sorted incomes
        $incomes = Income::where('user_id', $user->id)
            ->orderBy($sortField, $sortDirection)
            ->paginate(5);


        // Menghitung total pemasukan
        $totalIncome = Income::where('user_id', $user->id)->sum('amount');

        $title = 'Delete Data!';
        $text = "Anda yakin ingin menghapus?";
        confirmDelete($title, $text);

        // Group income data by month and calculate total income for each month
        $incomeData = $incomes->groupBy(function ($income) {
            return Carbon::parse($income->date)->format('F Y');
        })->map(function ($groupedIncomes) {
            return $groupedIncomes->sum('amount');
        });

        // Months for chart labels
        $months = $incomeData->keys()->toArray();

        return view('income.index', compact('incomes', 'totalIncome', 'incomeData', 'sortField', 'sortDirection', "months"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('income.create');
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

        // buat objek pemasukan
        $income = new Income();
        $income->user_id = auth()->id();
        $income->date = $request->input('date');
        $income->category = $request->input('category');
        $income->amount = $request->input('amount');
        $income->description = $request->input('description');

        // Simpan pemasukan baru ke database
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
}
