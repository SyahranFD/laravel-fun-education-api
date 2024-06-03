<?php

namespace App\Http\Controllers;

use App\Http\Requests\LaporanBulananRequest;
use App\Http\Resources\LaporanBulananResource;
use App\Models\LaporanBulanan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LaporanBulananController extends Controller
{
    public function store(LaporanBulananRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $laporanBulananData = $request->all();
        do {
            $laporanBulananData['id'] = 'laporan-bulanan-'.Str::uuid();
        } while (LaporanBulanan::where('id', $laporanBulananData['id'])->exists());

        $laporanBulanan = LaporanBulanan::create($laporanBulananData);
        $laporanBulanan = new LaporanBulananResource($laporanBulanan);

        $this->notification($laporanBulanan->user_id);

        return $this->resStoreData($laporanBulanan);
    }

    public function index()
    {
        return LaporanBulananResource::collection(LaporanBulanan::all());
    }

    public function showById($id)
    {
        $laporanBulanan = LaporanBulanan::find($id);
        if (! $laporanBulanan) {
            return $this->resDataNotFound('Laporan Bulanan');
        }

        return new LaporanBulananResource($laporanBulanan);
    }

    public function showCurrent(Request $request)
    {
        $user = auth()->user();
        $month = $request->query('month');
        $laporanBulanan = LaporanBulanan::where('user_id', $user->id)
            ->whereMonth('created_at', $month)
            ->first();
        if (! $laporanBulanan) {
            return $this->resDataNotFound('Laporan Bulanan');
        }

        return new LaporanBulananResource($laporanBulanan);
    }

    public function showFilter(Request $request)
    {
        $userId = $request->query('userId');
        $month = $request->query('month');
        $laporanBulanan = LaporanBulanan::where('user_id', $userId)
            ->whereMonth('created_at', $month)
            ->first();
        if (! $laporanBulanan) {
            return $this->resDataNotFound('Laporan Bulanan');
        }

        return new LaporanBulananResource($laporanBulanan);
    }

    public function update(LaporanBulananRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $laporanBulanan = LaporanBulanan::find($id);
        if (! $laporanBulanan) {
            return $this->resDataNotFound('Laporan Bulanan');
        }

        $laporanBulanan->update($request->all());

        return new LaporanBulananResource($laporanBulanan);
    }

    public function delete($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $laporanBulanan = LaporanBulanan::find($id);
        if (! $laporanBulanan) {
            return $this->resDataNotFound('Laporan Bulanan');
        }

        $laporanBulanan->delete();

        return $this->resDataDeleted('Laporan Bulanan');
    }

    public function notification($id)
    {
        $FcmToken = User::find($id)->fcm_token;
        $message = CloudMessage::fromArray([
            'token' => $FcmToken,
            'notification' => [
                'title' => 'Laporan Bulanan',
                'body' => 'Anda memiliki laporan bulanan yang belum dibaca.',
            ],
        ]);

        Firebase::messaging()->send($message);
    }
}
