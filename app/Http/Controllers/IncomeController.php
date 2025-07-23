<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Menu;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
 
class IncomeController extends Controller
{
public function index(Request $request)
{
    $user = Auth::user();
    $menus = Menu::all();

    $sortField = $request->query('field', 'date');
    $sortDirection = $request->query('sort', 'asc') === 'asc' ? 'asc' : 'desc';
    $validSortFields = ['date', 'amount'];
    if (!in_array($sortField, $validSortFields)) {
        $sortField = 'date';
    }

    $kasirIds = collect();
    $query = Income::query();

    if ($user->role === 'kasir') {
        $query->where('user_id', $user->id);
    } elseif ($user->role === 'owner') {
        $kasirIds = User::where('role', 'kasir')->pluck('id');
        $query->whereIn('user_id', $kasirIds);
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

    $rawData = $query->orderBy($sortField, $sortDirection)->get();
    $grouped = $rawData->groupBy('id_incomes');
    $groupedKeys = $grouped->keys();

    $page = $request->get('page', 1);
    $perPage = 10;

    $offset = ($page - 1) * $perPage;
    $pagedGroupKeys = $groupedKeys->slice($offset, $perPage);

    // Ambil hanya grup yang masuk halaman ini
    $incomesGrouped = $pagedGroupKeys->mapWithKeys(function ($key) use ($grouped) {
        return [$key => $grouped[$key]];
    });

    $incomes = new LengthAwarePaginator(
        $incomesGrouped->flatten(1),
        $grouped->count(),
        $perPage,
        $page,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    $totalIncomePerGroup = $incomesGrouped->map(fn($group) => $group->sum('total_price'));
    $totalIncome = $totalIncomePerGroup->sum();

    $monthlyIncomes = $rawData->groupBy(fn($i) => Carbon::parse($i->date)->format('F'));
    $monthlyData = $monthlyIncomes->map(fn($group) => $group->groupBy('id_incomes')->map(fn($g) => $g->sum('total_price'))->sum())->values()->toArray();
    $months = $monthlyIncomes->keys()->toArray();

    $dailyIncomes = $rawData->groupBy(fn($i) => Carbon::parse($i->date)->format('Y-m-d'));
    $dailyData = $dailyIncomes->map(fn($group) => $group->groupBy('id_incomes')->map(fn($g) => $g->sum('total_price'))->sum());
    $daily = [
        'labels' => $dailyData->keys()->toArray(),
        'values' => $dailyData->values()->toArray(),
    ];

    $groupedById = $rawData->groupBy('id_incomes');
    $categorySums = $groupedById->flatMap(fn($group) => $group->groupBy('category')->map(fn($items, $category) => [
        'category' => $category,
        'total' => $items->sum('total_price')
    ]));
    $groupedByCategory = $categorySums->groupBy('category')->map(fn($items) => collect($items)->sum('total'));
    $categoryData = [
        'labels' => $groupedByCategory->keys()->values(),
        'data' => $groupedByCategory->values()
    ];

    $availableYears = Income::selectRaw('YEAR(date) as year')
        ->when($user->role === 'kasir', fn($q) => $q->where('user_id', $user->id))
        ->when($user->role === 'owner', fn($q) => $q->whereIn('user_id', $kasirIds))
        ->distinct()
        ->orderByDesc('year')
        ->pluck('year');

    return view('income.index', compact(
        'incomes',
        'incomesGrouped',
        'totalIncome',
        'totalIncomePerGroup',
        'monthlyData',
        'dailyData',
        'daily',
        'categoryData',
        'months',
        'availableYears',
        'menus'
    ));
}


    public function create()
    {
        // Ambil semua menu yang tersedia
        $menus = Menu::where('availability', 'Tersedia')->get();

        return view('income.create', compact('menus'));
    }

    public function store(Request $request)
    {
        try {
            // Validasi input berdasarkan kondisi
            $request->validate([
                'date' => 'required|array',
                'menu_id' => 'required|array',
                'amount' => 'required|array',
                'description' => 'nullable|array',  // Validate description as string
                'quantity' => 'nullable|array',    // Validate quantity as numeric
                'price' => 'nullable|array',       // Validate price as numeric
            ]);

            // Membuat id_incomes di luar loop agar nilainya sama untuk semua entri
            $id_incomes = 'INC' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);  // 4 random digits after 'INC'

            $totalAmount = 0;  // Variable for total amount that will be saved

            // Loop through each order to calculate total amount
            foreach ($request->menu_id as $index => $menu_id) {
                $menu = Menu::find($menu_id);

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
                Log::info('Menu ID: ' . $menu_id);
                Log::info('Quantity: ' . $quantity);
                Log::info('Price: ' . $price);
                Log::info('Description: ' . $description);

                // Calculate total price for each order
                $totalPrice = $price * $quantity;

                // Add to totalAmount
                $totalAmount += $totalPrice;
            }

            // Loop through each order again to store the data with the same amount
            foreach ($request->menu_id as $index => $menu_id) {
                $menu = Menu::find($menu_id);

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
                $income = new Income();
                $income->id_incomes = $id_incomes; // Use the same id_incomes for all records
                $income->user_id = auth()->id();
                $income->date = $request->date[0];  // Use the first entered date for all records
                $income->category = $menu ? $menu->category : 'Tip'; // Save category from menu or 'Tip'
                $income->amount = $totalAmount;  // The same total amount for all entries
                $income->price = $price;
                $income->description = $description;  // Save the description (now it's guaranteed to be a string)
                $income->quantity = $quantity;
                $income->total_price = $totalPrice;  // Save total price for each item

                // Save the income data
                $income->save();
            }

            alert()->success('Success!', 'Data successfully added.');
            return redirect()->route('index.income');
        } catch (\Exception $e) {
            Log::error('Error while saving data: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit($id_incomes)
    {
        // Cari grup berdasarkan id_incomes
        $income = Income::where('id_incomes', $id_incomes)->get();

        $menus = Menu::all(); // Ambil semua menu untuk pilihan
        return view('income.edit', compact('income', 'menus'));
    }

    public function update(Request $request, $id_incomes)
    {
        try {
            $request->validate([
                'date' => 'required|array',
                'menu_id' => 'required|array',
                'amount' => 'required', // tidak array
                'description' => 'nullable|array',
                'quantity' => 'nullable|array',
                'price' => 'nullable|array',
            ]);

            $amount = $request->amount; // total harga akhir

            $incomes = Income::where('id_incomes', $id_incomes)->get();

            foreach ($incomes as $index => $income) {
                $income->quantity = $request->quantity[$index];
                $income->price = $request->price[$index];
                $income->description = $request->description[$index];
                $income->total_price = $income->price * $income->quantity;
                $income->date = $request->date[0];
                $income->amount = $amount; // <- ini bagian penting
                $income->save();
            }

            alert()->success('Success!', 'Data successfully updated.');
            return redirect()->route('index.income');
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
