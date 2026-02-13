<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori; // Import Model

class KategoriController extends Controller
{
    // READ: Show the list of categories
    public function index()
    {
        $kategori = Kategori::all();
        return view('kategori.index', compact('kategori'));
    }

    // CREATE (View): Show the form to add a new category
    public function create()
    {
        return view('kategori.create');
    }

    // CREATE (Action): Save the new category to database
    public function store(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|max:100']);

        Kategori::create($request->all());

        return redirect()->route('kategori.index')
                         ->with('success', 'Kategori berhasil ditambahkan!');
    }

    // UPDATE (View): Show the form to edit an existing category
    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    // UPDATE (Action): Save changes to the database
    public function update(Request $request, $id)
    {
        $request->validate(['nama_kategori' => 'required|max:100']);

        $kategori = Kategori::findOrFail($id);
        $kategori->update($request->all());

        return redirect()->route('kategori.index')
                         ->with('success', 'Kategori berhasil diupdate!');
    }

    // DELETE: Soft delete the category
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('kategori.index')
                         ->with('success', 'Kategori berhasil dihapus!');
    }
}