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

    // Menampilkan form edit
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('manage-menu.edit', compact('menu'));
    }

    // Menyimpan hasil edit
    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'menu' => 'required|string|max:255',
            'price' => 'required',
            'availability' => 'required|string',
        ]);

        $menu = Menu::findOrFail($id);

        $price = preg_replace('/[^\d]/', '', $request->price);

        $menu->update([
            'category' => $request->category,
            'menu' => $request->menu,
            'price' => $price,
            'availability' => $request->availability,
        ]);

        return redirect()->route('manage-menu.index')->with('success', 'Menu berhasil diperbarui!');
    }


    // Menghapus menu
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return redirect()->route('manage-menu.index')->with('success', 'Menu deleted successfully!');
    }

    public function create()
    {
        return view('manage-menu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'menu' => 'required|string|max:255',
            'price' => 'required',
        ]);

        $price = preg_replace('/[^\d]/', '', $request->price); // hilangkan Rp dan titik

        \App\Models\Menu::create([
            'category' => $request->category,
            'menu' => $request->menu,
            'price' => $price,
            'availability' => 'Belum Diatur',
        ]);

        return redirect()->route('manage-menu.index')->with('success', 'Menu berhasil ditambahkan!');
    }

}
