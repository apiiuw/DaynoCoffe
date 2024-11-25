<?php

namespace App\Http\Controllers;

use App\Models\bill;
use App\Notifications\BillReminderNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Models\expense;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        // Field dan arah sort default
        $sortField = $request->query('field', 'date');
        $sortDirection = $request->query('sort', 'asc') == 'asc' ? 'asc' : 'desc';

        // Validasi field sort untuk mencegah SQL injection
        $validSortFields = ['date', 'amount'];
        if (!in_array($sortField, $validSortFields)) {
            $sortField = 'date';
        }

        // Ambil data tagihan yang sudah di-sort
        $bills = Bill::where('user_id', $user->id)
            ->orderBy($sortField, $sortDirection)
            ->paginate(5);

        // Menghitung total tagihan
        $totalBill = Bill::where('user_id', $user->id)->sum('amount');

        $title = 'Delete Data!';
        $text = "Anda yakin ingin menghapus?";
        confirmDelete($title, $text);

        // Group data tagihan per bulan dan hitung total tagihan setiap bulan
        $billData = $bills->groupBy(function ($bill) {
            return Carbon::parse($bill->date)->format('F Y');
        })->map(function ($groupedBills) {
            return $groupedBills->sum('amount');
        });

        // Bulan untuk label chart
        $months = $billData->keys()->toArray();

        // Panggil metode untuk mengirim notifikasi tagihan
        $this->sendBillReminders();

        return view('bill.index', compact('bills', 'totalBill', 'billData', 'sortField', 'sortDirection', 'months'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bill.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'due_date' => 'required|date',
            'status' => 'required|boolean',
            'description' => 'nullable|string',
        ]);

        $user = Auth::user();

        //Buat Objek Tagihan
        $bill = new Bill();
        $bill->user_id = $user->id;
        $bill->date = $request->input('date');
        $bill->category = $request->input('category');
        $bill->amount = $request->input('amount');
        $bill->due_date = $request->input('due_date');
        $bill->status = $request->input('status');
        $bill->description = $request->input('description');

        // Simpan tagihan baru ke database
        $bill->save();

        // Redirect dengan pesan sukses
        alert()->success('Berhasil!', 'Data Berhasil Ditambah');
        return redirect()->route('index.bill');
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
        $bill = Bill::findOrFail($id);
        if (!in_array($bill->status, [0, 1])) {
            $bill->status = 0; // Set default to 'Belum Lunas' if status is not valid
        }
        return view('bill.edit', compact('bill'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'due_date' => 'required|date',
            'status' => 'required|boolean',
            'description' => 'nullable|string',
        ]);

        $bill = Bill::findOrFail($id);
        $bill->date = $request->date;
        $bill->category = $request->category;
        $bill->amount = $request->amount;
        $bill->due_date = $request->due_date;
        $bill->status = $request->status;
        $bill->description = $request->description;
        $bill->save();

        alert()->success('Berhasil!', 'Data Berhasil Diperbarui');
        return redirect()->route('index.bill');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bill = Bill::findOrFail($id);
        $bill->delete();
        alert()->success('Berhasil', 'Data Berhasil Dihapus');
        return redirect()->route('index.bill');
    }



    public function sendBillReminders()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');

        // Ambil semua tagihan yang jatuh tempo hari ini
        $bills = Bill::where('user_id', $user->id)
            ->whereDate('due_date', $today) // Pastikan hanya tagihan yang jatuh tempo hari ini
            ->get();

        foreach ($bills as $bill) {
            // Tambahkan notifikasi ke icon lonceng di navbar
            echo "<script>({ message: 'New bill reminder: Pay your {$bill->category} bill' })</script>";
        }
    }

    public function getDueBills()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');

        // Ambil semua tagihan yang jatuh tempo hari ini
        $bills = Bill::where('user_id', $user->id)
            ->whereDate('due_date', $today)
            ->get();

        return response()->json($bills);
    }

    public function markAsPaid($id, Request $request)
    {
        $bill = Bill::findOrFail($id);

        // Tandai tagihan sebagai lunas
        $bill->status = 'Lunas';
        $bill->save();

        // Buat entri pengeluaran baru
        $expense = new Expense();
        $expense->user_id = Auth::id();
        $expense->date = now()->format('Y-m-d'); // Atau $bill->date jika ingin menggunakan tanggal tagihan
        $expense->category = $bill->category;
        $expense->amount = $bill->amount;
        $expense->description = "Pembayaran untuk tagihan: " . $bill->category;
        $expense->save();

        alert()->success('Berhasil!', 'Tagihan telah ditandai sebagai lunas.');
        return redirect()->route('index.bill');
    }
}
