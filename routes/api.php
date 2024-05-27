<?php

use App\Http\Controllers\AlurBelajarController;
use App\Http\Controllers\CatatanDaruratController;
use App\Http\Controllers\LaporanBulananController;
use App\Http\Controllers\LaporanHarianController;
use App\Http\Controllers\ShiftMasukController;
use App\Http\Controllers\TabunganController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/users')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);

    Route::get('/index', [UserController::class, 'index']);
    Route::get('/show/{id}', [UserController::class, 'showById']);
    Route::get('/show-current', [UserController::class, 'showCurrent'])->middleware('auth:sanctum');

    Route::put('/update-admin/{id}', [UserController::class, 'updateAdmin'])->middleware('auth:sanctum');
    Route::delete('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [UserController::class, 'delete'])->middleware('auth:sanctum');
});

Route::prefix('/shift-masuk')->group(function () {
    Route::post('/store', [ShiftMasukController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [ShiftMasukController::class, 'index']);
    Route::get('/show/{id}', [ShiftMasukController::class, 'showById']);
    Route::get('/show-current', [ShiftMasukController::class, 'showCurrent'])->middleware('auth:sanctum');

    Route::put('/update/{id}', [ShiftMasukController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [ShiftMasukController::class, 'delete'])->middleware('auth:sanctum');
});

Route::prefix('/catatan-darurat')->group(function () {
    Route::post('/store', [CatatanDaruratController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [CatatanDaruratController::class, 'index']);
    Route::get('/show/{id}', [CatatanDaruratController::class, 'showById']);
    Route::get('/show', [CatatanDaruratController::class, 'show']);

    Route::put('/update/{id}', [CatatanDaruratController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [CatatanDaruratController::class, 'delete'])->middleware('auth:sanctum');
});

Route::prefix('/laporan-harian')->group(function () {
    Route::post('/store', [LaporanHarianController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [LaporanHarianController::class, 'index']);
    Route::get('/show/{id}', [LaporanHarianController::class, 'showById']);
    Route::get('/show-current', [LaporanHarianController::class, 'showCurrent'])->middleware('auth:sanctum');
    Route::get('/show-filter', [LaporanHarianController::class, 'showFilter']);

    Route::put('/update/{id}', [LaporanHarianController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [LaporanHarianController::class, 'delete'])->middleware('auth:sanctum');
});

Route::prefix('/laporan-bulanan')->group(function () {
    Route::post('/store', [LaporanBulananController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [LaporanBulananController::class, 'index']);
    Route::get('/show/{id}', [LaporanBulananController::class, 'showById']);
    Route::get('/show-current', [LaporanBulananController::class, 'showCurrent'])->middleware('auth:sanctum');
    Route::get('/show-filter', [LaporanBulananController::class, 'showFilter']);

    Route::put('/update/{id}', [LaporanBulananController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [LaporanBulananController::class, 'delete'])->middleware('auth:sanctum');
});

Route::prefix('/alur-belajar')->group(function () {
    Route::post('/store', [AlurBelajarController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [AlurBelajarController::class, 'index']);
    Route::get('/show/{id}', [AlurBelajarController::class, 'showById']);
    Route::get('/show-current', [AlurBelajarController::class, 'showCurrent'])->middleware('auth:sanctum');

    Route::put('/update/{id}', [AlurBelajarController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [AlurBelajarController::class, 'delete'])->middleware('auth:sanctum');
});

Route::prefix('/tabungan')->group(function () {
    Route::post('/store', [TabunganController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [TabunganController::class, 'index']);
    Route::get('/show/{id}', [TabunganController::class, 'showById']);
    Route::get('/show-current', [TabunganController::class, 'showCurrent'])->middleware('auth:sanctum');

    Route::put('/update/{id}', [TabunganController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [TabunganController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('/transaksi')->group(function () {
    Route::post('/store', [TransaksiController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [TransaksiController::class, 'index']);
    Route::get('/show/{id}', [TransaksiController::class, 'showById']);
    Route::get('/show-current', [TransaksiController::class, 'showCurrent'])->middleware('auth:sanctum');

    Route::put('/update/{id}', [TransaksiController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [TransaksiController::class, 'delete'])->middleware('auth:sanctum');
});
