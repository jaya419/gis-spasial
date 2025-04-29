<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LokasiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LokasiController::class, 'index'])->name('wilayah.index');
Route::post('/lokasi', [LokasiController::class, 'store']);
Route::put('/lokasi/{id}', [LokasiController::class, 'update']);
Route::delete('/lokasi/{id}', [LokasiController::class, 'destroy']);
Route::get('/search', [LokasiController::class, 'search']);

Route::get('/daftar-lokasi', [LokasiController::class, 'daftarLokasi'])->name('lokasi.daftar');
Route::delete('/lokasi/{id}', [LokasiController::class, 'destroy'])->name('lokasi.destroy');
