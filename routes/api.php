<?php

use App\Http\Controllers\CatatanDaruratController;
use App\Http\Controllers\ShiftMasukController;
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
