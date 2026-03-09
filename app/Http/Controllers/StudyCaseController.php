<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudyCaseController extends Controller
{
    public function tabelBiasa()
    {
        return view('study-case.barang.tabel-biasa');
    }

    public function tabelDataTables()
    {
        return view('study-case.barang.tabel-datatables');
    }
    public function select2Kota()
    {
        return view('study-case.select2-kota');
    }
}
