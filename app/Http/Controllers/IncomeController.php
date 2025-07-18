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

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        $kasirIds = collect(); // inisialisasi untuk owner

        // Setup query builder
        $query = Income::query();

        if ($user->role === 'kasir') {
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'owner') {
            $kasirIds = User::where('role', 'kasir')->pluck('id');
            $query->whereIn('user_id', $kasirIds);
        }

        // Filter bulan dan tahun
        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        // Mengambil data pemasukan dengan pagination
        $incomes = $query->orderBy($sortField, $sortDirection)
            ->with('user')  // Mengambil data user untuk nama kasir
            ->paginate(10)  // Menggunakan paginate untuk mendapatkan data yang dipaginasi
            ->withQueryString();  // Menyertakan query string saat pagination

        // Mengelompokkan data berdasarkan id_incomes setelah dipaginasi
        $incomesGrouped = $incomes->getCollection()->groupBy('id_incomes');

        // Menghitung total harga per grup
        $totalIncomePerGroup = $incomesGrouped->map(function ($group) {
            // Menghitung total harga grup dengan menjumlahkan harga keseluruhan
            $totalPerGroup = $group->sum('total_price');
            return $totalPerGroup;
        });

        // Total pemasukan keseluruhan: Menjumlahkan total harga dari setiap grup
        $totalIncome = $totalIncomePerGroup->sum(); // Menjumlahkan total harga dari seluruh grup

        // Data untuk grafik bulanan
        $monthlyData = $incomesGrouped->map(function ($group) {
            return $group->sum('total_price'); // Jumlahkan total_price per grup untuk bulanan
        });

        // Data untuk grafik harian
        $dailyIncomes = (clone $query)->orderBy('date')->get()->groupBy(function ($income) {
            return Carbon::parse($income->date)->format('Y-m-d');
        });

        $dailyData = $dailyIncomes->map(function ($group) {
            return $group->sum('total_price'); // Jumlahkan total_price per hari
        });

        // Data kategori
        $categoryData = (clone $query)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->pluck('total', 'category');

        $daily = [
            'labels' => $dailyIncomes->keys()->toArray(),
            'values' => $dailyData->values()->toArray()
        ];

        // Bulan untuk chart
        $months = $incomesGrouped->keys()->toArray();

        // Dropdown tahun - Mengambil distinct tahun dari Income
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
            'incomes', // Mengirimkan data yang dipaginasi
            'incomesGrouped', // Mengirimkan data yang dikelompokkan
            'totalIncome', // Total pemasukan keseluruhan berdasarkan grup
            'totalIncomePerGroup', // Total per grup (untuk setiap grup)
            'monthlyData', // Data untuk chart bulanan
            'dailyData', // Data untuk chart harian
            'daily', // Data untuk grafik harian
            'categoryData',
            'months', // Bulan untuk chart
            'availableYears', // Mengirimkan tahun yang tersedia ke view
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
