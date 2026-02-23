<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// 1. SHARED ROUTES (Dashboard)
Route::middleware(['auth'])->group(function () {
    // Rute untuk Verifikasi OTP
    Route::get('/verify', [App\Http\Controllers\VerificationController::class, 'index'])->name('verify');
    Route::post('/verify', [App\Http\Controllers\VerificationController::class, 'verify']);
    Route::get('/send-otp', [App\Http\Controllers\VerificationController::class, 'sendOtp']);
});

Route::get('auth/google', [LoginController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

Route::get ('/register', fn () => view('auth.register'))->name('register');
Route::post('register', [AuthController::class, 'register']);

// 2. ADMIN ROUTES
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('kategori', KategoriController::class);
    Route::resource('buku', BukuController::class);
}); 


// 3. VISITOR ROUTES & DASHBOARD (Dijaga ketat oleh CheckStatus)
Route::middleware(['auth', 'check_status'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    Route::get('/visitor/kategori', [VisitorController::class, 'kategori'])->name('visitor.kategori');
    Route::get('/visitor/buku', [VisitorController::class, 'buku'])->name('visitor.buku');
});

// Rute untuk Download PDF
Route::get('/cetak-landscape', [App\Http\Controllers\PdfController::class, 'landscape']);
Route::get('/cetak-potrait', [App\Http\Controllers\PdfController::class, 'potrait']);