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

        return $this->resShowData($laporanHarian);
    }

    public function index()
    {
        return LaporanHarianResource::collection(LaporanHarian::all());
    }

    public function showById($id)
    {
        $laporanHarian = LaporanHarian::find($id);
        if (! $laporanHarian) {
            return $this->resDataNotFound("Laporan Harian");
        }
        return new LaporanHarianResource($laporanHarian);
    }

    public function showCurrent()
    {
        $user = auth()->user();
        $laporanHarian = LaporanHarian::where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->first();
        if (! $laporanHarian) {
            return $this->resDataNotFound("Laporan Harian");
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
            return $this->resDataNotFound("Laporan Harian");
        }

        $laporanHarian->update($request->all());
        $laporanHarian = new LaporanHarianResource($laporanHarian);

        return $laporanHarian;
    }

    public function delete($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $laporanHarian = LaporanHarian::find($id);
        if (! $laporanHarian) {
            return $this->resDataNotFound("Laporan Harian");
        }

        $laporanHarian->delete();
        return $this->resDataDeleted();
    }
}
