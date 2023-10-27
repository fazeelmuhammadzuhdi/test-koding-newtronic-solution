<?php

use App\Http\Controllers\AntrianController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\TaskController;
use App\Models\Antrian;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
})->middleware('guest');


Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::resource('/nasabah', NasabahController::class)->middleware(['auth']);

Route::controller(AntrianController::class)->name('antrian.')->middleware(['auth'])->group(function () {
    Route::get('/antrian/cetak-pdf-teller', 'cetakPdfTeller')->name('cetak-pdf-teller');
    Route::get('/antrian/cetak-pdf-cs', 'cetakPdfCs')->name('cetak-pdf-cs');
    Route::get('/antrian/daftar-antrian-nasabah-teller', 'daftarAntrianNasabahTeller')->name('nasabah-teller');
    Route::get('/antrian/daftar-antrian-nasabah-cs', 'daftarAntrianNasabahCs')->name('nasabah-cs');
    Route::post('/antrian/store', 'store')->name('store');
    Route::post('/antrian/panggil', 'panggilAntrian')->name('panggil');
});
