<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function landscape()
    {
        $pdf = Pdf::loadView('pdf.landscape')->setPaper('a4', 'landscape');
        
        return $pdf->stream('Sertifikat_Kegiatan.pdf');
    }

    public function potrait()
    {
        $pdf = Pdf::loadView('pdf.potrait')->setPaper('a4', 'portrait');
        
        return $pdf->stream('Undangan_Fakultas.pdf');
    }
}