<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku; 
use App\Models\Kategori;

class VisitorController extends Controller
{
    public function buku()
    {
        $buku = Buku::with('kategori')->get();
        
        return view('visitor.buku', compact('buku'));
    }

    public function kategori()
    {
        $kategori = Kategori::all();
        return view('visitor.kategori', compact('kategori'));
    }
}