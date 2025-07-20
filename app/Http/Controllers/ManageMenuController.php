<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class ManageMenuController extends Controller
{
    // Menampilkan seluruh menu, urut berdasarkan kategori (abjad)
    public function index()
    {
        $menus = Menu::orderBy('category', 'asc')->get();
        return view('manage-menu.index', compact('menus'));
    }

    public function create()
    {
        return view('manage-menu.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'category' => 'required|string|max:255',
            'menu' => 'required|string|max:255',
            'price' => 'required',
        ]);

        // Hilangkan karakter non-numerik pada harga
        $price = preg_replace('/[^\d]/', '', $request->price); // Hilangkan Rp dan titik

        // Jika kategori adalah "Other", gunakan nilai kategori lain
        $category = $request->category;
        if ($category == 'Other' && !empty($request->other_category)) {
            $category = $request->other_category;  // Gunakan input kategori lain
        }

        // Simpan menu ke database
        \App\Models\Menu::create([
            'category' => $category,  // Simpan kategori yang benar
            'menu' => $request->menu,
            'price' => $price,
            'availability' => 'Belum Diatur',
        ]);

        return redirect()->route('manage-menu.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('manage-menu.edit', compact('menu'));
    }

    // Menyimpan hasil edit
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'category' => 'required|string|max:255',
            'menu' => 'required|string|max:255',
            'price' => 'required',
            'availability' => 'required|string',
        ]);

        // Temukan menu berdasarkan id
        $menu = Menu::findOrFail($id);

        // Hilangkan karakter non-numerik pada harga
        $price = preg_replace('/[^\d]/', '', $request->price);

        // Jika kategori adalah "Other", gunakan nilai kategori lain
        $category = $request->category;
        if ($category == 'Other' && !empty($request->other_category)) {
            $category = $request->other_category; 
        }

        // Update menu ke database
        $menu->update([
            'category' => $category, 
            'menu' => $request->menu,
            'price' => $price,
            'availability' => $request->availability,
        ]);

        return redirect()->route('manage-menu.index')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return redirect()->route('manage-menu.index')->with('success-destroy', 'Menu deleted successfully!'); 
    }
}
