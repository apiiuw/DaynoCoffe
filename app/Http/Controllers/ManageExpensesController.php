<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpensesCategory;

class ManageExpensesController extends Controller
{
    public function index()
    {
        $expanses = ExpensesCategory::all();
        return view('manage-expanses.index', compact('expanses'));
    }

    public function create()
    {
        $expanses = ExpensesCategory::all();
        return view('manage-expanses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'item' => 'required|string|max:255',
            'price' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        // Hilangkan format "Rp" dan titik agar bisa disimpan sebagai angka
        $cleanNominal = preg_replace('/[^0-9]/', '', $request->price);

        // Cek jika kategori adalah "Other"
        $category = $request->category;
        if ($category == 'Other') {
            $category = $request->other_category;  // Gunakan input kategori lainnya
        }

        ExpensesCategory::create([
            'category' => $category,
            'item' => $request->item,
            'price' => $cleanNominal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('manage-expanses.index')->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $expanse = ExpensesCategory::findOrFail($id);
        return view('manage-expanses.edit', compact('expanse'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'item' => 'required|string|max:255',
            'price' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $cleanNominal = preg_replace('/[^0-9]/', '', $request->price);

        // Temukan kategori berdasarkan id
        $expanse = ExpensesCategory::findOrFail($id);

        // Cek jika kategori adalah "Other"
        $category = $request->category;
        if ($category == 'Other') {
            $category = $request->other_category;  // Gunakan input kategori lainnya
        }

        // Update data pengeluaran
        $expanse->update([
            'category' => $category,
            'item' => $request->item,
            'price' => $cleanNominal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('manage-expanses.index')->with('success', 'Data berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $expanse = ExpensesCategory::findOrFail($id);
        $expanse->delete();

        return redirect()->route('manage-expanses.index')->with('success-destroy', 'Data berhasil dihapus.');
    }


}
