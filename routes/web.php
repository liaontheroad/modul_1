<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\VisitorController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// 1. SHARED ROUTES (Dashboard)
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

// 2. ADMIN ROUTES
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('kategori', KategoriController::class);
    Route::resource('buku', BukuController::class);
}); // <--- MOVED THIS UP! (Closes the Admin group here)


// 3. VISITOR ROUTES 
Route::middleware(['auth', 'role:visitor'])->group(function () {
    Route::get('/visitor/kategori', [VisitorController::class, 'kategori'])->name('visitor.kategori');
    Route::get('/visitor/buku', [VisitorController::class, 'buku'])->name('visitor.buku');
});