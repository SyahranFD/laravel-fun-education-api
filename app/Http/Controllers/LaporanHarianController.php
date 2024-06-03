<?php

namespace App\Http\Controllers;

use App\Http\Requests\LaporanHarianRequest;
use App\Http\Resources\LaporanHarianResource;
use App\Models\LaporanHarian;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LaporanHarianController extends Controller
{
    public function store(LaporanHarianRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $laporanHarianData = $request->all();
        do {
            $laporanHarianData['id'] = 'laporan-harian-'.Str::uuid();
        } while (LaporanHarian::where('id', $laporanHarianData['id'])->exists());

        $laporanHarian = LaporanHarian::create($laporanHarianData);
        $laporanHarian = new LaporanHarianResource($laporanHarian);

        $this->notification($laporanHarian->user_id);

        return $this->resStoreData($laporanHarian);
    }

    public function index()
    {
        return LaporanHarianResource::collection(LaporanHarian::all());
    }

    public function showById($id)
    {
        $laporanHarian = LaporanHarian::find($id);
        if (! $laporanHarian) {
            return $this->resDataNotFound('Laporan Harian');
        }

        return new LaporanHarianResource($laporanHarian);
    }

    public function showCurrent(Request $request)
    {
        $user = auth()->user();
        $date = $request->query('date');
        $laporanHarian = LaporanHarian::where('user_id', $user->id)
            ->whereDate('created_at', $date)
            ->first();
        if (! $laporanHarian) {
            return $this->resDataNotFound('Laporan Harian');
        }

        return new LaporanharianResource($laporanHarian);
    }

    public function showFilter(Request $request)
    {
        $userId = $request->query('userId');
        $date = $request->query('date');
        $laporanHarian = LaporanHarian::where('user_id', $userId)
            ->whereDate('created_at', $date)
            ->first();
        if (! $laporanHarian) {
            return $this->resDataNotFound('Laporan Harian');
        }

        return new LaporanHarianResource($laporanHarian);
    }

    public function update(LaporanHarianRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $laporanHarian = LaporanHarian::find($id);
        if (! $laporanHarian) {
            return $this->resDataNotFound('Laporan Harian');
        }

        $laporanHarian->update($request->all());

        return new LaporanHarianResource($laporanHarian);
    }

    public function delete($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $laporanHarian = LaporanHarian::find($id);
        if (! $laporanHarian) {
            return $this->resDataNotFound('Laporan Harian');
        }

        $laporanHarian->delete();

        return $this->resDataDeleted('Laporan Harian');
    }

    public function notification($id)
    {
        $FcmToken = User::find($id)->fcm_token;
        $message = CloudMessage::fromArray([
            'token' => $FcmToken,
            'notification' => [
                'title' => 'Laporan Harian',
                'body' => 'Anda memiliki laporan harian yang belum dibaca.',
            ],
        ]);

        Firebase::messaging()->send($message);
    }
}
