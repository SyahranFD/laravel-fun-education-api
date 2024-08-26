<?php

use App\Http\Controllers\CatatanDaruratFileController;
use App\Http\Controllers\TokenResetPasswordController;
use App\Http\Controllers\CalendarFileController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\CalendarCategoryController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\TugasUserImageController;
use App\Http\Controllers\TugasUserController;
use App\Http\Controllers\TugasImageController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\AlurBelajarController;
use App\Http\Controllers\CatatanDaruratController;
use App\Http\Controllers\FirebasePushController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\LaporanHarianController;
use App\Http\Controllers\MinimumApplicationController;
use App\Http\Controllers\SavingApplicationController;
use App\Http\Controllers\ShiftMasukController;
use App\Http\Controllers\SavingController;
use App\Http\Controllers\TransactionController;
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
    Route::get('/show-secret/{id}', [UserController::class, 'showByIdSecret'])->middleware('auth:sanctum');
    Route::get('/show-current', [UserController::class, 'showCurrent'])->middleware('auth:sanctum');


    Route::put('/update-admin/{id}', [UserController::class, 'updateAdmin'])->middleware('auth:sanctum');
    Route::put('/reset-password', [UserController::class, 'resetPassword']);
    Route::delete('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [UserController::class, 'delete'])->middleware('auth:sanctum');

    Route::put('/update-fcm-token', [FirebasePushController::class, 'setToken'])->middleware('auth:sanctum');
    Route::put('/update-verify/{id}', [UserController::class, 'verify'])->middleware('auth:sanctum');
    Route::post('/send-notification/{id}', [FirebasePushController::class, 'notification']);
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

Route::prefix('/catatan-darurat-file')->group(function () {
    Route::post('/store', [CatatanDaruratFileController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [CatatanDaruratFileController::class, 'index']);
    Route::get('/show/{id}', [CatatanDaruratFileController::class, 'show']);

    Route::delete('/delete/{id}', [CatatanDaruratFileController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('/laporan-harian')->group(function () {
    Route::post('/store', [LaporanHarianController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [LaporanHarianController::class, 'index']);
    Route::get('/show/{id}', [LaporanHarianController::class, 'showById']);
    Route::get('/show-current', [LaporanHarianController::class, 'showCurrent'])->middleware('auth:sanctum');
    Route::get('/show-current-point', [LaporanHarianController::class, 'showCurrentPoint'])->middleware('auth:sanctum');
    Route::get('/statistic', [LaporanHarianController::class, 'showStatistic'])->middleware('auth:sanctum');
    Route::get('/statistic-new', [LaporanHarianController::class, 'showStatisticNew'])->middleware('auth:sanctum');
    Route::get('/show-filter', [LaporanHarianController::class, 'showFilter']);
    Route::get('/user', [LaporanHarianController::class, 'user']);
    Route::get('/show-available', [LaporanHarianController::class, 'showAvailable'])->middleware('auth:sanctum');

    Route::put('/update', [LaporanHarianController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/delete', [LaporanHarianController::class, 'delete'])->middleware('auth:sanctum');
});

Route::prefix('/alur-belajar')->group(function () {
    Route::post('/store', [AlurBelajarController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [AlurBelajarController::class, 'index']);
    Route::get('/show/{id}', [AlurBelajarController::class, 'showById']);
    Route::get('/show-current', [AlurBelajarController::class, 'showCurrent'])->middleware('auth:sanctum');
    Route::get('/show-by-user/{userId}', [AlurBelajarController::class, 'showByUserId']);

    Route::put('/update/{id}', [AlurBelajarController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [AlurBelajarController::class, 'delete'])->middleware('auth:sanctum');
});

Route::prefix('/tabungan')->group(function () {
    Route::post('/store', [SavingController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [SavingController::class, 'index']);
    Route::get('/show/{id}', [SavingController::class, 'showById']);
    Route::get('/show-current', [SavingController::class, 'showCurrent'])->middleware('auth:sanctum');
    Route::get('/show-by-user/{userId}', [SavingController::class, 'showByUserId']);

    Route::put('/update/{id}', [SavingController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [SavingController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('/transaksi')->group(function () {
    Route::post('/store', [TransactionController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [TransactionController::class, 'index']);
    Route::get('/show/{id}', [TransactionController::class, 'showById']);
    Route::get('/show-current', [TransactionController::class, 'showCurrent'])->middleware('auth:sanctum');
    Route::get('/show-by-user/{userId}', [TransactionController::class, 'showByUserId']);
    Route::get('/statistic', [TransactionController::class, 'showStatistic'])->middleware('auth:sanctum');

    Route::put('/update/{id}', [TransactionController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [TransactionController::class, 'delete'])->middleware('auth:sanctum');
});

Route::prefix('/pengajuan-tabungan')->group(function () {
    Route::post('/store', [SavingApplicationController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [SavingApplicationController::class, 'index']);
    Route::get('/show/{id}', [SavingApplicationController::class, 'showById']);
    Route::get('/show-current', [SavingApplicationController::class, 'showCurrent'])->middleware('auth:sanctum');
    Route::get('/show-by-user/{userId}', [SavingApplicationController::class, 'showByUserId']);

    Route::put('/update/{id}', [SavingApplicationController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [SavingApplicationController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('/album')->group(function () {
    Route::post('/store', [AlbumController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [AlbumController::class, 'index']);
    Route::get('/show/{id}', [AlbumController::class, 'showById']);

    Route::put('/update/{id}', [AlbumController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [AlbumController::class, 'delete'])->middleware('auth:sanctum');
});

Route::prefix('/gallery')->group(function () {
    Route::post('/store', [GalleryController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [GalleryController::class, 'index']);
    Route::get('/show/{id}', [GalleryController::class, 'showById']);

    Route::put('/update/{id}', [GalleryController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [GalleryController::class, 'delete'])->middleware('auth:sanctum');
});

Route::prefix('/minimum-pengajuan')->group(function () {
    Route::post('/store', [MinimumApplicationController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [MinimumApplicationController::class, 'index']);
    Route::get('/show/{id}', [MinimumApplicationController::class, 'showById']);
    Route::get('/show-current', [MinimumApplicationController::class, 'showCurrent'])->middleware('auth:sanctum');

    Route::put('/update/{id}', [MinimumApplicationController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [MinimumApplicationController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('/tugas')->group(function () {
    Route::post('/store', [TugasController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [TugasController::class, 'index']);
    Route::get('/show/{id}', [TugasController::class, 'showById'])->middleware('auth:sanctum');
    Route::get('/show-current', [TugasController::class, 'showCurrent'])->middleware('auth:sanctum');
    Route::get('/show-status-count', [TugasController::class, 'showStatusCount']);
    Route::get('/show-current-status-count', [TugasController::class, 'showCurrentStatusTugasUserCount'])->middleware('auth:sanctum');

    Route::put('/update/{id}', [TugasController::class, 'update'])->middleware('auth:sanctum');
    Route::put('/update-status/{id}', [TugasController::class, 'updateStatus'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [TugasController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('/tugas-image')->group(function () {
    Route::post('/store', [TugasImageController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [TugasImageController::class, 'index']);
    Route::get('/show/{id}', [TugasImageController::class, 'show']);

    Route::put('/update/{id}', [TugasImageController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [TugasImageController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('/tugas-user')->group(function () {
    Route::post('/store', [TugasUserController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [TugasUserController::class, 'index']);
    Route::get('/show/{id}', [TugasUserController::class, 'show']);
    Route::get('/show-by-tugas-id/{tugasId}', [TugasUserController::class, 'showByTugasId']);
    Route::get('/show-current/{tugasId}', [TugasUserController::class, 'showCurrent'])->middleware('auth:sanctum');
    Route::get('/statistic', [TugasUserController::class, 'showStatistic'])->middleware('auth:sanctum');
    Route::get('statistic-new', [TugasUserController::class, 'showStatisticNew'])->middleware('auth:sanctum');

    Route::put('/update/{id}', [TugasUserController::class, 'update'])->middleware('auth:sanctum');
    Route::put('/send-grade/{id}', [TugasUserController::class, 'sendGrade'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [TugasUserController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('/tugas-user-image')->group(function () {
    Route::post('/store', [TugasUserImageController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [TugasUserImageController::class, 'index']);
    Route::get('/show/{id}', [TugasUserImageController::class, 'show']);

    Route::put('/update/{id}', [TugasUserImageController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [TugasUserImageController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('/leaderboard')->group(function () {
    Route::get('/index', [LeaderboardController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/point', [LeaderboardController::class, 'point'])->middleware('auth:sanctum');
});

Route::prefix('/calendar')->group(function () {
    Route::post('/store', [CalendarController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [CalendarController::class, 'index']);
    Route::get('/show/{id}', [CalendarController::class, 'show']);

    Route::put('/update/{id}', [CalendarController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [CalendarController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('/calendar-category')->group(function () {
    Route::post('/store', [CalendarCategoryController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [CalendarCategoryController::class, 'index']);
    Route::get('/show/{id}', [CalendarCategoryController::class, 'show']);

    Route::put('/update/{id}', [CalendarCategoryController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [CalendarCategoryController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('/calendar-file')->group(function () {
    Route::post('/store', [CalendarFileController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index', [CalendarFileController::class, 'index']);
    Route::get('/show/{id}', [CalendarFileController::class, 'show']);

    Route::delete('/delete/{id}', [CalendarFileController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('/otp')->group(function () {
    Route::post('/store', [OtpController::class, 'store']);
    Route::post('/check', [OtpController::class, 'check']);

    Route::get('/index', [OtpController::class, 'show']);
    Route::get('/show-current', [OtpController::class, 'showCurrent'])->middleware('auth:sanctum');
    Route::get('/show-by-email', [OtpController::class, 'showByEmail']);
});

Route::prefix('/token-reset-password')->group(function () {
    Route::get('/index', [TokenResetPasswordController::class, 'index']);
    Route::get('/show', [TokenResetPasswordController::class, 'show']);
});
