<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpansesCategory;

class ManageExpansesController extends Controller
{
    public function index()
    {
        $expanses = ExpansesCategory::all();
        return view('manage-expanses.index', compact('expanses'));
    }

    public function create()
    {
        $expanses = ExpansesCategory::all();
        return view('manage-expanses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'item' => 'required|string|max:255',
            'nominal' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        // Hilangkan format "Rp" dan titik agar bisa disimpan sebagai angka
        $cleanNominal = preg_replace('/[^0-9]/', '', $request->nominal);

        ExpansesCategory::create([
            'category' => $request->category,
            'item' => $request->item,
            'nominal' => $cleanNominal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('manage-expanses.index')->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $expanse = ExpansesCategory::findOrFail($id);
        return view('manage-expanses.edit', compact('expanse'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'item' => 'required|string|max:255',
            'nominal' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $cleanNominal = preg_replace('/[^0-9]/', '', $request->nominal);

        $expanse = ExpansesCategory::findOrFail($id);
        $expanse->update([
            'category' => $request->category,
            'item' => $request->item,
            'nominal' => $cleanNominal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('manage-expanses.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $expanse = ExpansesCategory::findOrFail($id);
        $expanse->delete();

        return redirect()->route('manage-expanses.index')->with('success-destroy', 'Data berhasil dihapus.');
    }


}
