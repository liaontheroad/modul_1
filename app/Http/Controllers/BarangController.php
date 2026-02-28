<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{

    public function index()
    {
        $barang = DB::table('barang')->orderBy('timestamp', 'desc')->get();
        return view('barang.index', compact('barang'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:50',
            'harga' => 'required|numeric'
        ]);

        DB::table('barang')->insert([
            'nama' => $request->nama,
            'harga' => $request->harga
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        DB::table('barang')->where('id_barang', $id)->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus!');
    }
    public function cetak(Request $request)
    {
        if(!$request->items) {
            return back()->with('error', 'Pilih minimal 1 permen dulu untuk dicetak!');
        }

        $barang_terpilih = DB::table('barang')->whereIn('id_barang', $request->items)->get();

        $jumlah_kosong = ($request->y - 1) * 5 + ($request->x - 1);

        $dataCetak = [];
        
        for ($i = 0; $i < $jumlah_kosong; $i++) {
            $dataCetak[] = null; 
        }

        foreach ($barang_terpilih as $b) {
            $dataCetak[] = $b;
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('barang.pdf', compact('dataCetak'))->setPaper('a4', 'portrait');
        return $pdf->stream('Tag_Harga_TnJ_108.pdf');
    }
}
