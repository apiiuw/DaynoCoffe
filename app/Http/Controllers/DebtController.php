<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;  
use App\Models\debt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index(Request $request)
    {
        $user = Auth::user();

        // Default sort field and direction
        $sortField = $request->query('field', 'date');
        $sortDirection = $request->query('sort', 'asc') === 'asc' ? 'asc' : 'desc';

        // Validasi field sort untuk mencegah SQL injection
        $validSortFields = ['date', 'amount'];
        if (!in_array($sortField, $validSortFields)) {
            $sortField = 'date';
        }

        // Ambil data hutang yang disortir
        $debts = Debt::where('user_id', $user->id)
            ->orderBy($sortField, $sortDirection)
            ->paginate(5);

        // Hitung total hutang
        $totalDebt = Debt::where('user_id', $user->id)->sum('amount');

        // Konfirmasi delete
        $title = 'Delete Data!';
        $text = "Anda yakin ingin menghapus?";
        confirmDelete($title, $text);

        // Data bulanan untuk chart
        $debtData = $debts->groupBy(function ($debt) {
            return Carbon::parse($debt->date)->format('F Y');
        })->map(function ($groupedDebts) {
            return $groupedDebts->sum('amount');
        });

        $months = $debtData->keys()->toArray();
        // Ambil semua data hutang (bukan dari $debts paginated)
$allDebts = Debt::where('user_id', $user->id)->get();

// Harian
$start = Carbon::now()->subDays(30);
$end = Carbon::now();

$dailyDebtRaw = Debt::where('user_id', $user->id)
    ->whereBetween('date', [$start, $end])
    ->select(DB::raw('DATE(date) as day'), DB::raw('SUM(amount) as total'))
    ->groupBy('day')
    ->orderBy('day')
    ->get();

$dailyDebtLabels = $dailyDebtRaw->pluck('day');
$dailyDebtValues = $dailyDebtRaw->pluck('total');

        return view('debt.index', compact(
            'debts',
            'totalDebt',
            'debtData',
            'sortField',
            'sortDirection',
            'months',
            'dailyDebtLabels',
            'dailyDebtValues'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('debt.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi input
        $request->validate([
            'category' => 'required|string',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'due_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $user = Auth::user();

        // buat objek hutang
        $debt = new Debt();
        $debt->user_id = auth()->id();
        $debt->category = $request->input('category');
        $debt->date = $request->input('date');
        $debt->amount = $request->input('amount');
        $debt->due_date = $request->input('due_date');
        $debt->description = $request->input('description');

        // Simpan hutang baru ke database
        $debt->save();

        alert()->success('Berhasil!', 'Data Berhasil Ditambah');
        return redirect()->route('index.debt');
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
        $debt = Debt::findOrFail($id);
        return view('debt.edit', compact('debt'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'debt_type' => 'required|string',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'due_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $debt = Debt::findOrFail($id);
        $debt->debt_type = $request->debt_type;
        $debt->date = $request->date;
        $debt->amount = $request->amount;
        $debt->due_date = $request->due_date;
        $debt->description = $request->description;
        $debt->save();

        alert()->success('Berhasil!', 'Data Berhasil Diperbarui');
        return redirect()->route('index.debt');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $debt = Debt::findOrFail($id);
        $debt->delete();
        alert()->success('Berhasil', 'Data Berhasil Dihapus');
        return redirect()->route('index.debt');
    }

    public function sendDebtReminders()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');

        // Ambil semua tagihan yang jatuh tempo hari ini
        $debts = Debt::where('user_id', $user->id)
            ->whereDate('due_date', $today) // Pastikan hanya tagihan yang jatuh tempo hari ini
            ->get();

        foreach ($debts as $debt) {
            // Tambahkan notifikasi ke icon lonceng di navbar
            echo "<script>({ message: 'New bill reminder: Pay your {$debt->debt_type} bill' })</script>";
        }
    }

    public function getDueDebts()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');

        // Ambil semua tagihan yang jatuh tempo hari ini
        $debts = Debt::where('user_id', $user->id)
            ->whereDate('due_date', $today)
            ->get();

        return response()->json($debts);
    }
}
